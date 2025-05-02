<?php

namespace Core\Repositories;

use Core\DTOs\CreateProductDTO;
use Core\DTOs\ProductDTO;
use Core\DTOs\ProductPreviewDTO;
use Core\DTOs\UpdateProductDTO;
use Core\DTOs\UserDTO;
use Core\Repositories\UserRepository;
use InvalidArgumentException;
use ProductFilter;

class ProductRepository extends BaseRepository
{

    public function findById(string $id): ?ProductDTO
    {
        $row = $this->db->query("SELECT * FROM Product WHERE product_id = ?", [$id])->find();

        if ($row) {
            $users = new UserRepository();
            $seller = $users->findById($row['seller_id']);

            $images = $this->getImagesFor($id);
            $displayImageUrl = "";
            $imageUrls = [];

            foreach ($images as $image) {
                $imageUrls[] = $image["url"];
                if ($image["is_display_img"]) {
                    $displayImageUrl = $image["url"];
                }
            }

            $product = ProductDTO::fromRow($row);
            $product->seller = $seller;
            $product = $displayImageUrl;
            $product = $imageUrls;
        }

        return null;
    }

    public function findAll(ProductFilter $filter): array
    {
        $sql = <<<SQL
            SELECT * FROM Product p
            LEFT JOIN Seller s
            ON p.seller_id = s.user_id 
            LEFT JOIN User u 
            ON u.user_id = s.user_id
            LEFT JOIN ProductImage pi 
            ON p.product_id = pi.product_id
            {$filter->getWhereClause()}
            {$filter->getLimitClause()}
            ORDER BY p.id;
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
        $row = $this->db->query("SELECT {$fields} FROM Product WHERE product_id = ?", [$id])->find();

        return $this->previewFromRow($row);
    }

    public function findAllPreviews(ProductFilter $filter): ?array
    {
        $fields = ProductPreviewDTO::toFields("p");
        $sql = <<<SQL
            SELECT {$fields}, pi.img_url FROM Product p
            LEFT JOIN ProductImage pi 
            ON p.product_id = pi.product_id
            {$filter->getWhereClause()}
            {$filter->getLimitClause()}
            ORDER BY p.id
            WHERE pi.is_display_img = True;
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

    public function getImagesFor(string $id): ?array
    {
        return $this->db->query("SELECT * FROM ProductImage WHERE product_id = ?", [$id])->findAll();
    }

    public function create(CreateProductDTO $product): ?ProductPreviewDTO
    {
        $alreadyExists = $this->db->query("SELECT product_id FROM Product WHERE name = ?", [$product->name])->find();
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

        return $this->db->query("DELETE * FROM Product WHERE product_id = ?", [$id])->wasSuccessful();
    }

    public function exists(string $id)
    {
        $result = $this->db->query("SELECT 1 FROM Product WHERE product_id = ? LIMIT 1")->find();

        return !empty($result);
    }

    public function findByName(string $productName): ?ProductPreviewDTO
    {
        $fields = ProductPreviewDTO::toFields();
        $row = $this->db->query("SELECT {$fields} FROM Product WHERE name = ?", [$productName])->find();

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
        $this->executeIfExists($id, "UPDATE Product SET quant_in_stock = quant_in_stock + ? WHERE product_id = ?", [$id, $stock]);
    }

    public function increaseViews(string $id) 
    {
        $this->executeIfExists($id, "UPDATE Product SET views = views + 1 WHERE product_id = ?", [$id]);
    }

    public function applyDiscount(string $id, int $discountPct) {
        $this->executeIfExists($id, "UPDATE Product SET pct_discount = ? WHERE product_id = ?", [$discountPct, $id]);
    }

    public function removeDiscount(string $id) {
        $this->executeIfExists($id, "UPDATE Product SET pct_discount = 0 WHERE product_id = ?", [$id]);
    }

    public function updateAvgRating(string $id) {
        $sql = <<<SQL
            UPDATE Product
            SET target_column = (
                SELECT AVG(rating)
                FROM Review
                WHERE product_id = ?
            )
            WHERE product_id = ?;
        SQL;
        $this->executeIfExists($id, $sql, [$id, $id]);

    }

    public function updateImages($id, array $imageUrls, string $displayImageUrl)
    {
        // Get images
        $rows = $this->db->query("SELECT * FROM ProductImage WHERE product_id = ?", [$id])->findAll();

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
            "DELETE FROM ProductImage WHERE img_id IN ({implode(', ', array_fill(0, count($delete), '?'))})",
            $delete
        );

        // Insert images
        $this->insertImages($id, $imageUrls);

        // Update display image
        $this->db->query("UPDATE ProductImage SET is_display_image = false WHERE product_id = ?", [$id]);
        $this->db->query("UPDATE ProductImage SET is_display_image = True WHERE img_url = ?", [$displayImageUrl]);
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
            INSERT INTO ProductImage
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
                FROM ProductImage 
                WHERE product_id = ? 
                AND is_display_img = True
            SQL;
            $url = $this->db->query($sql, [$row["product_id"]])->find();

            $productPreview->displayImageUrl = $url;

            return $productPreview;
        }

        return null;
    }
}
