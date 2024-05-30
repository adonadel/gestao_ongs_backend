<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Address::class;
    }


}
