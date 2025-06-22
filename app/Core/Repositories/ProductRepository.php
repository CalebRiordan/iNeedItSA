<?php

namespace Core\Repositories;

use Core\DTOs\CreateProductDTO;
use Core\DTOs\ProductDTO;
use Core\DTOs\ProductPreviewDTO;
use Core\DTOs\UpdateProductDTO;
use Core\DTOs\UserDTO;
use Core\Repositories\UserRepository;
use InvalidArgumentException;
use Core\Filters\ProductFilter;

class ProductRepository extends BaseRepository
{

    public function findById(string $id): ?ProductDTO
    {
        $row = $this->db->query("SELECT * FROM product WHERE product_id = ?", [$id])->find();

        if ($row) {
            $users = new UserRepository();
            $seller = $users->findById($row['seller_id']);

            $images = $this->getImagesFor($id);
            $displayImageUrl = "";
            $imageUrls = [];

            foreach ($images as $image) {
                $url = $image["img_url"] ?? "";
                $imageUrls[] = $url;
                if ($image["is_display_img"]) {
                    $displayImageUrl = $url;
                }
            }

            $product = ProductDTO::fromRow($row);
            $product->seller = $seller;
            $product->displayImageUrl = $displayImageUrl;
            $product->imageUrls = $imageUrls;

            return $product;
        }

        return null;
    }

    public function findAll(?ProductFilter $filter = null): array
    {
        $filter ??= new ProductFilter();

        $where = $filter->getWhereClause('p');
        $limit = $filter->getLimitClause();
        $offset = $filter->getOffsetClause();

        $sql = <<<SQL
            SELECT p.*, u.*, s.*, b.*, pi.img_url, pi.is_display_img, COALESCE(SUM(oi.quantity), 0) AS sales 
            FROM product p
            LEFT JOIN user u
            ON p.seller_id = u.user_id 
            LEFT JOIN seller s
            ON p.seller_id = s.user_id 
            LEFT JOIN buyer b
            ON p.seller_id = b.user_id 
            LEFT JOIN product_image_url pi 
            ON p.product_id = pi.product_id AND pi.is_display_img = 1
            LEFT JOIN order_item oi 
            ON p.product_id = oi.product_id
            {$where} 
            GROUP BY p.product_id
            {$limit}
            {$offset}
            ORDER BY p.product_id;
        SQL;
        $rows = $this->db->query($sql, $filter->getValues())->findAll();

        $products = [];

        foreach ($rows as $row) {
            $productId = $row['product_id'];

            if (!isset($products[$productId])) {
                // Add new ProductDTO
                $products[$productId] = ProductDTO::fromRow($row);

                // Add seller profile and sales attributes
                $seller = UserDTO::fromRow($row);
                $products[$productId]->seller = $seller;
                $products[$productId]->sales = $row['sales'];
            }

            // Add image urls
            if ($row['img_url']) {
                if ($row['is_display_img']) {
                    $products[$productId]->displayImageUrl = $row['img_url'];
                } else {
                    $products[$productId]->imageUrls[] = $row['img_url'];
                }
            }
        }

        return array_values($products);
    }

    public function findPreviewById(string $id): ?ProductPreviewDTO
    {
        $fields = ProductPreviewDTO::toFields();
        $row = $this->db->query("SELECT {$fields} FROM product WHERE product_id = ?", [$id])->find();

        return $this->previewFromRow($row);
    }

    public function findAllPreviews(?ProductFilter $filter = null): array
    {
        $filter ??= new ProductFilter();

        $fields = ProductPreviewDTO::toFields('p');
        $where = $filter->getWhereClause('p');
        $orderBy = $filter->getOrderByClause('p.product_id');
        $limit = $filter->getLimitClause();
        $offset = $filter->getOffsetClause();

        $sql = <<<SQL
            SELECT {$fields}, COALESCE(pi.img_url, '') as img_url FROM product p
            LEFT JOIN product_image_url pi
            ON p.product_id = pi.product_id AND pi.is_display_img = True
            {$where}
            {$orderBy}
            {$limit}
            {$offset}
        SQL;

        $rows = $this->db->query($sql, $filter->getValues())->findAll();

        $products = [];
        foreach ($rows as $row) {
            $product = ProductPreviewDTO::fromRow($row);
            $product->displayImageUrl = $row['img_url'];
            $products[] = $product;
        }

        return $products;
    }

    public function create(CreateProductDTO $product): ?ProductPreviewDTO
    {
        $alreadyExists = $this->findByName($product->name);
        if ($alreadyExists) {
            return null;
        }

        // Save new image and attach URL to product
        $product->displayImageUrl = saveImage($product->displayImageFile, 'product', '/uploads/product_imgs');

        $fields = CreateProductDTO::toFields();
        $placeholders = $product->placeholders();
        // Insert Product record
        $sql = <<<SQL
            INSERT INTO product 
            ({$fields})
            VALUES ({$placeholders})
        SQL;

        $newId = $this->db->query($sql, $product->getMappedValues())->newId();
        $this->insertImages($newId, $product->imageUrls, $product->displayImageUrl);

        return $this->findPreviewById($newId);
    }

    public function update(UpdateProductDTO $product): bool
    {
        // Check existing product
        $id = $product->id;
        $existingProduct = $this->findById($id);

        $nameTaken = $existingProduct->name !== $product->name && $this->findByName($product->name);
        if (!$existingProduct || $nameTaken) {
            return false;
        }

        // If image changed, save new image and attach URL to product
        if ($product->imageChanged) {
            $product->displayImageUrl = saveImage($product->displayImageFile, 'product', '/uploads/product_imgs');
            removeImage($existingProduct->displayImageUrl);
            $this->updateDisplayImage($product);
        }

        $sets = $product->getMappedUpdateSet();
        // Update Product table
        $sql = <<<SQL
            UPDATE product
            SET {$sets}
            WHERE product_id = ?
        SQL;

        if (!$this->db->query($sql, [$id])->wasSuccessful()) return false;

        // FUTURE: Update list of images in product_image_url table
        // $this->updateImages($id, $product->imageUrls, $product->displayImageUrl);

        return true;
    }

    public function delete(string $id): bool
    {
        return $this->db->query("DELETE FROM product WHERE product_id = ?", [$id])->hadEffect();
    }

    public function exists(string $id)
    {
        $result = $this->db->query("SELECT 1 FROM product WHERE product_id = ? LIMIT 1", [$id])->find();
        return !empty($result);
    }

    public function getFeaturedProducts(): array
    {
        $fields = ProductPreviewDTO::toFields("p");
        $sql = <<<SQL
            SELECT {$fields}, pi.img_url FROM product p
            LEFT JOIN product_image_url pi
            ON p.product_id = pi.product_id
            ORDER BY views DESC, avg_rating DESC 
            LIMIT 16;
        SQL;

        $rows = $this->db->query($sql)->findAll();
        $products = [];
        foreach ($rows as $row) {
            $product = ProductPreviewDTO::fromRow($row);
            $product->displayImageUrl = $row['img_url'] ?? "";
            $products[] = $product;
        }

        return $products;
    }

    public function getImagesFor(string $id): ?array
    {
        return $this->db->query("SELECT * FROM product_image_url WHERE product_id = ?", [$id])->findAll();
    }

    public function findByName(string $productName): ?ProductPreviewDTO
    {
        $fields = ProductPreviewDTO::toFields();
        $row = $this->db->query("SELECT {$fields} FROM product WHERE name = ?", [$productName])->find();

        return $this->previewFromRow($row);
    }

    public function hasBeenBoughtBy(string $productId, string $userId){
        $sql = <<<SQL
            SELECT 1 FROM order_item oi 
            LEFT JOIN `order` o
            ON oi.order_id = o.order_id
            LEFT JOIN buyer b
            ON o.user_id = b.user_id
            WHERE oi.product_id = ?
            AND b.user_id = ?
        SQL;

        $result = $this->db->query($sql, [$productId, $userId])->find();

        return !empty($result);
    }

    private function executeIfExists($id, string $query, array $params = [])
    {
        if ($this->exists($id)) {
            $this->db->query($query, $params);
            return;
        }
        throw new InvalidArgumentException("No product found with ID '{$id}'");
    }

    public function updateStock(string $id, int $stock)
    {
        $this->executeIfExists($id, "UPDATE product SET quant_in_stock = quant_in_stock + ? WHERE product_id = ?", [$id, $stock]);
    }

    public function addView(string $id)
    {
        // Increment product views
        $this->executeIfExists($id, "UPDATE product SET views = views + 1 WHERE product_id = ?", [$id]);

        // Increment seller's total_views
        $sql = <<<SQL
            UPDATE seller 
            SET total_views = total_views + 1 
            WHERE user_id = (SELECT seller_id FROM product WHERE product_id = ?)
        SQL;
        $this->db->query($sql, [$id]);
    }

    public function applyDiscount(string $id, int $discountPct)
    {
        $this->executeIfExists($id, "UPDATE product SET pct_discount = ? WHERE product_id = ?", [$discountPct, $id]);
    }

    public function removeDiscount(string $id)
    {
        $this->executeIfExists($id, "UPDATE product SET pct_discount = 0 WHERE product_id = ?", [$id]);
    }

    public function updateAvgRating(string $id)
    {
        $sql = <<<SQL
            UPDATE product
            SET target_column = (
                SELECT AVG(rating)
                FROM review
                WHERE product_id = ?
            )
            WHERE product_id = ?;
        SQL;
        $this->executeIfExists($id, $sql, [$id, $id]);
    }

    public function updateImages($id, array $imageUrls, string $displayImageUrl)
    {
        // Get images
        $rows = $this->db->query("SELECT * FROM product_image_url WHERE product_id = ?", [$id])->findAll();

        $delete = [];
        $imageUrls[] = $displayImageUrl;

        foreach ($rows as $row) {
            $url = $row['img_url'];

            if (!in_array($url, $imageUrls)) {
                $delete[] = $row["img_id"];
            } else {
                unset($imageUrls[$url]);
            }
        }

        // Delete images
        $this->db->query(
            "DELETE FROM product_image_url WHERE img_id IN ({implode(', ', array_fill(0, count($delete), '?'))})",
            $delete
        );

        // Insert images
        $this->insertImages($id, $imageUrls);

        // Update display image
        $this->db->query("UPDATE product_image_url SET is_display_image = False WHERE product_id = ?", [$id]);
        $this->db->query("UPDATE product_image_url SET is_display_image = True WHERE img_url = ?", [$displayImageUrl]);
    }

    public function insertImages(string $productId, array $imageUrls, ?string $displayImageUrl = null)
    {
        $sets = [];
        foreach ($imageUrls as $url) {
            $sets[] = "(?, ?, False)";
        }
        $params = [];
        foreach ($imageUrls as $url) {
            $params[] = $productId;
            $params[] = $url;
        }

        if ($displayImageUrl) {
            $sets[] = "(?, ?, True)";
            $params[] = $productId;
            $params[] = $displayImageUrl;
        }

        if (empty($sets)) {
            return;
        }

        $insertSetString = implode(", ", $sets);

        $sql = <<<SQL
            INSERT INTO product_image_url
            (product_id, img_url, is_display_img)
            VALUES {$insertSetString};
        SQL;

        $this->db->query($sql, $params);
    }

    private function updateDisplayImage($product)
    {
        // Check if a display image already exists for this product
        $existing = $this->db->query(
            "SELECT img_id FROM product_image_url WHERE product_id = ? AND is_display_img = 1",
            [$product->id]
        )->find();

        if ($existing) {
            // Update existing image url
            if (!$this->db->query(
                "UPDATE product_image_url SET img_url = ? WHERE img_id = ?",
                [$product->displayImageUrl, $existing['img_id']]
            )->wasSuccessful()) {
                return false;
            }
        } else {
            // Insert new image record as display image
            $this->insertImages($product->id, $product->imageUrls, $product->displayImageUrl);
        }
    }

    private function previewFromRow(?array $row): ?ProductPreviewDTO
    {
        if ($row) {
            $productPreview = ProductPreviewDTO::fromRow($row);

            $sql = <<<SQL
                SELECT img_url 
                FROM product_image_url 
                WHERE product_id = ? 
                AND is_display_img = True
            SQL;
            $url = $this->db->query($sql, [$row["product_id"]])->find()['img_url'];

            $productPreview->displayImageUrl = $url ?? "";

            return $productPreview;
        }

        return null;
    }

    public function getCount(?ProductFilter $filter): int
    {
        $filter ??= new ProductFilter();

        $where = $filter->getWhereClause();
        $sql = <<<SQL
            SELECT COUNT(*) FROM product
            {$where}
        SQL;

        $result = $this->db->query($sql, $filter->getValues())->find();
        return reset($result);
    }

    public function getCountOnId(string $id): int
    {
        $sql = <<<SQL
            SELECT COUNT(*) FROM product
            WHERE product_id = ?
        SQL;

        $result = $this->db->query($sql, $id)->find();
        return reset($result);
    }
}
