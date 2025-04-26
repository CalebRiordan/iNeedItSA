<?php

namespace Core;

use BaseDTO;

class CommunityDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $desc,
        public string $dateCreated,
        public string $founderId,
        /** @var UserPreviewDTO[] */
        public array $members,
    ) {}

    protected function setSqlMapping(): array
    {
        return [
            "id" => "id",
            "name" => "name",
            "desc" => "desc",
            "date_created" => "dateCreated",
            "founder_id" => "founderId",
            "members" => "members",
        ];
    }
}
