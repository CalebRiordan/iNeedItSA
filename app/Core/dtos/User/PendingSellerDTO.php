<?php

namespace Core\DTOs;

class PendingSellerDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "id",
        "first_name" => "firstName",
        "last_name" => "lastName",
        "email" => "email",
    ];

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $idDocUrl,
        public string $poaDocUrl,
        public string $dateSubmitted
    ) {}

    public static function fromRows(array $rows): array
    { 
        $array = [];

        foreach ($rows as $row) {
            /** @var PendingSellerDto $pendingSeller */
            $pendingSeller = parent::fromRow($row);

            $pendingSeller->idDocUrl = $row["copy_id_url"];
            $pendingSeller->poaDocUrl = $row["poa_url"];
            $pendingSeller->dateSubmitted = $row["date_submitted"];

            $array[] = $pendingSeller;
        }

        return $array;
    }
}
