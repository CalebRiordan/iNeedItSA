<?php

namespace Core\DTOs;

class OrderItemDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "item_id" => "id",
        "order_id" => "orderId",
        "product_id" => "productId",
        "quantity" => "quantity",
        "price_at_purchase" => "price"
    ];

    public int $totalCost = 0; // Derived
    public string $displayImageUrl = "";
    public string $productName = "";

    public function __construct(
        public string $id,
        public string $orderId,
        public string $productId,
        public int $quantity,
        public float $price
    ) {}

    /** Initializes the product information properties for each item derived from a row */
    public static function fromRowsAdditional(array $rows): array
    {
        $array = [];
        foreach ($rows as $row) {
            /** @var OrderItemDto $item */
            $item = parent::fromRow($row);

            $item->totalCost = $item->price * $item->quantity;
            $item->displayImageUrl = $row['img_url'] ?? "";
            $item->productName = $row['name'];

            $array[] = $item;
        }

        return $array;
    }
}
