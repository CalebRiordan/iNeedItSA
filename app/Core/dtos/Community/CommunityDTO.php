<?php

namespace Core\DTOs;

class CommunityDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "comm_id" => "id",
        "name" => "name",
        "description" => "description",
        "date_created" => "dateCreated",
        "founder_id" => "founderId",
    ];

    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $dateCreated,
        public string $founderId,
        /** @var UserPreviewDTO[] */
        public array $members = [],
    ) {}
}
