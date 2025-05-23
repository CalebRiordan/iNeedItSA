<?php

namespace Core\DTOs;

enum Role: string
{
    case Buyer = 'buyer';
    case Seller = 'seller';
}