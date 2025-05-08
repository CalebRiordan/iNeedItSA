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
        $sql = <<<SQL
            SELECT * FROM product p
            LEFT JOIN seller s
            ON p.seller_id = s.user_id 
            LEFT JOIN user u 
            ON u.user_id = s.user_id
            LEFT JOIN product_image_url pi 
            ON p.product_id = pi.product_id
            {$filter->getWhereClause()}
            {$filter->getLimitClause()}
            {$filter->getOffsetClause()}
            ORDER BY p.product_id;
        SQL;
        $rows = $this->db->query($sql, $filter->getValues())->findAll();

        $products = [];

        foreach ($rows as $row) {
            $productId = $row['product_id'];

            if (!isset($products[$productId])) {
                // Add new ProductDTO
                $products[$productId] = ProductDTO::fromRow($row);

                $seller = UserDTO::fromRow($row);
                $products[$productId]->seller = $seller;
            }

            if ($row['image_url']) {
                if ($row['is_display_img']) {
                    $products[$productId]->displayImageUrl = $row['image_url'];
                } else {
                    $products[$productId]->imageUrls[] = $row['image_url'];
                }
            }
        }

        return array_values($products);
    }

    public function findPreview(string $id): ?ProductPreviewDTO
    {
        $fields = ProductPreviewDTO::toFields();
        $row = $this->db->query("SELECT {$fields} FROM product WHERE product_id = ?", [$id])->find();

        return $this->previewFromRow($row);
    }

    public function findAllPreviews(?ProductFilter $filter = null): ?array
    {
        $filter ??= new ProductFilter();
        $fields = ProductPreviewDTO::toFields("p");
        $sql = <<<SQL
            SELECT {$fields}, COALESCE(pi.img_url, "") as img_url FROM product p
            LEFT JOIN product_image_url pi 
            ON p.product_id = pi.product_id AND pi.is_display_img = True
            {$filter->getWhereClause()}
            {$filter->getOrderByClause('p.product_id')}
            {$filter->getLimitClause()}
            {$filter->getOffsetClause()}
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
        return $this->db->query("SELECT * FROM product_image WHERE product_id = ?", [$id])->findAll();
    }

    public function create(CreateProductDTO $product): ?ProductPreviewDTO
    {
        $alreadyExists = $this->db->query("SELECT product_id FROM product WHERE name = ?", [$product->name])->find();
        if ($alreadyExists) {
            return null;
        }

        // Insert Product record
        $sql = <<<SQL
            INSERT INTO Product 
            ({CreateProductDTO::toFields()})
            VALUES ({$product->getMappedValues()})
        SQL;

        $newId = $this->db->query($sql, $product->getMappedValues())->newId();
        $this->insertImages($newId, $product->imageUrls, $product->displayImageUrl);

        return $this->findPreview($newId);
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

        // Update Product table
        $sql = <<<SQL
            UPDATE Product
            SET {$product->getMappedUpdateSet()}
            WHERE product_id = ?
        SQL;

        if (!$this->db->query($sql, [$id])->wasSuccessful()) return false;

        // Update Images in ProductImage table
        $this->updateImages($id, $product->imageUrls, $product->displayImageUrl);

        return true;
    }

    public function delete(string $id): bool
    {
        if ($this->exists($id)) {
            return false;
        }

        return $this->db->query("DELETE * FROM product WHERE product_id = ?", [$id])->wasSuccessful();
    }

    public function exists(string $id)
    {
        $result = $this->db->query("SELECT 1 FROM product WHERE product_id = ? LIMIT 1")->find();

        return !empty($result);
    }

    public function findByName(string $productName): ?ProductPreviewDTO
    {
        $fields = ProductPreviewDTO::toFields();
        $row = $this->db->query("SELECT {$fields} FROM product WHERE name = ?", [$productName])->find();

        return $this->previewFromRow($row);
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

    public function increaseViews(string $id) 
    {
        $this->executeIfExists($id, "UPDATE product SET views = views + 1 WHERE product_id = ?", [$id]);
    }

    public function applyDiscount(string $id, int $discountPct) {
        $this->executeIfExists($id, "UPDATE product SET pct_discount = ? WHERE product_id = ?", [$discountPct, $id]);
    }

    public function removeDiscount(string $id) {
        $this->executeIfExists($id, "UPDATE product SET pct_discount = 0 WHERE product_id = ?", [$id]);
    }

    public function updateAvgRating(string $id) {
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
        $rows = $this->db->query("SELECT * FROM product_image WHERE product_id = ?", [$id])->findAll();

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
            "DELETE FROM product_image WHERE img_id IN ({implode(', ', array_fill(0, count($delete), '?'))})",
            $delete
        );

        // Insert images
        $this->insertImages($id, $imageUrls);

        // Update display image
        $this->db->query("UPDATE product_image SET is_display_image = False WHERE product_id = ?", [$id]);
        $this->db->query("UPDATE product_image SET is_display_image = True WHERE img_url = ?", [$displayImageUrl]);
    }

    public function insertImages(string $id, array $imageUrls, ?string $displayImageUrl = null)
    {
        $sets = [];
        for ($i = 0; $i < count($imageUrls); $i++) {
            $sets[] = "({$id}, {?}, False)";
        }
        $insertSetString = implode(", ", $sets);

        $displayImageSet = "";
        if ($displayImageUrl) {
            $displayImageSet = "({$id}, ?, True)";
            $imageUrls[] = $displayImageUrl;
        }

        $sql = <<<SQL
            INSERT INTO product_image
            (product_id, img_url, is_display_img)
            VALUES {$insertSetString},
            $displayImageSet;
        SQL;

        $this->db->query($sql, $imageUrls);
    }

    private function previewFromRow(?array $row): ?ProductPreviewDTO
    {
        if ($row) {
            $productPreview = ProductPreviewDTO::fromRow($row);

            $sql = <<<SQL
                SELECT img_url 
                FROM product_image 
                WHERE product_id = ? 
                AND is_display_img = True
            SQL;
            $url = $this->db->query($sql, [$row["product_id"]])->find()['img_url'];

            $productPreview->displayImageUrl = $url ?? "";

            return $productPreview;
        }

        return null;
    }
}
