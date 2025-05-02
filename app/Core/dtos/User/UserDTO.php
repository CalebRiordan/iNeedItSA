<?php

namespace Core\DTOs;

enum Role: string
{
    case Buyer = 'Buyer';
    case Seller = 'Seller';
}

class UserDTO extends BaseDTO
{
    public static array $roleClasses = [
        'Buyer' => BuyerProfileDTO::class,
        'Seller' => SellerProfileDTO::class,
    ];

    protected static array $sqlMapping = [
        "user_id" => "id",
        "first_name" => "firstName",
        "last_name" => "lastName",
        "email" => "email",
        "password" => "password",
        "phone_no" => "phoneNo",
        "location" => "location",
        "address" => "address",
        "profile_pic_url" => "profilePicUrl",
        "date_joined" => "dateJoined",
        "is_buyer" => "",
        "is_seller" => "",
    ];

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
        public string $phoneNo,
        public string $location,
        public string $address,
        public ?string $profilePicUrl = null,
        public string $dateJoined,
        public ?BuyerProfileDTO $buyerProfile = null,
        public ?SellerProfileDTO $sellerProfile = null
    ) {
    }

    public function isBuyer()
    {
        return $this->buyerProfile !== null ? True : False;
    }

    public function isSeller()
    {
        return $this->sellerProfile !== null ? True : False;
    }

    public static function fromRow(array $row): ?UserDTO
    {
        $user = UserDTO::fromRow($row);
        if (!$user){
            return null;
        }
        $user->allocateProfiles($row);
        return $user;
    }

    public function allocateProfiles($row)
    {
        try {
            if ($row['is_buyer']) {
                $this->buyerProfile = new BuyerProfileDTO(
                    $row['num_orders'],
                    $row['ship_address']
                );
            }

            if ($row['is_seller']) {
                $this->sellerProfile = new SellerProfileDTO(
                    $row['num_sales'],
                    $row['total_ads'],
                    $row['total_views'],
                    $row['verified'],
                    $row['date_verified'],
                    $row['date_registered'],
                );
            }
        } catch (\Throwable $th) {
            throw new \Exception("Provided row field names do not map to either Buyer or Seller profiles");
        }
    }
}
