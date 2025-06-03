<?php

namespace Core\DTOs;

class CreateOrderDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "userId",
        "date" => "date",
        "ship_address" => "shipAddress",
        "location" => "location",
        "total" => "totalCost",
    ];

    public string $date;

    public function __construct(
        public string $userId,
        public string $shipAddress,
        public string $location,
        public string $totalCost,
        /** @var CreateOrderItemDTO[] */
        public array $items,
    ) {
        $this->date = date('Y-m-d');
    }

    public function itemsPlaceholderSets()
    {
        $itemPlaceholderSet = '(' . CreateOrderItemDTO::placeholders() . ')';
        $count = count($this->items);

        return implode(', ', array_fill(0, $count, $itemPlaceholderSet));
    }

    public function itemsValues($orderId)
    {
        $values = [];
        foreach ($this->items as $item) {
            $values[] = $item->productId;
            $values[] = $orderId;
            $values[] = $item->quantity;
            $values[] = $item->price;
        }
        return $values;
    }
}
