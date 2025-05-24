<?php

namespace Core\DTOs;

class UserDTO extends BaseDTO
{

    /**
     * Maps user role names to their corresponding profile DTO class names.
     *
     * @var array<string, BaseDTO>
     */
    public static array $roleClasses = [
        'buyer' => BuyerProfileDTO::class,
        'seller' => SellerProfileDTO::class,
    ];

    protected static array $sqlMapping = [
        "user_id" => "id",
        "first_name" => "firstName",
        "last_name" => "lastName",
        "email" => "email",
        "password" => "password",
        "phone_no" => "phoneNo",
        "location" => "location",
        "province" => "province",
        "address" => "address",
        "profile_pic_url" => "profilePicUrl",
        "date_joined" => "dateJoined",
        "is_buyer" => "",
        "is_seller" => "",
    ];

    protected static array $excludeFromReflection = ['roleClasses'];

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
        public string $phoneNo,
        public string $location,
        public string $province,
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

    public static function fromRow(?array $row): ?static
    {
        $user = parent::fromRow($row);
        if (!$user){
            return null;
        }
        $user->allocateProfiles($row);
        return $user;
    }

    public function allocateProfiles($row)
    {
        // Initialize variables to prevent access before initialization
        $this->buyerProfile = null;
        $this->sellerProfile = null;

        if ($row['is_buyer']) {
            $this->buyerProfile = new BuyerProfileDTO(
                $row['ship_address'],
                $row['num_orders']
            );
        }
        
        if ($row['is_seller']) {
            $this->sellerProfile = new SellerProfileDTO(
                $row['verified'],
                $row['products_sold'],
                $row['total_views'],
                $row['date_registered'],
                $row['date_verified'],
            );
        }
    }
}
