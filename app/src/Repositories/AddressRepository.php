<?php

namespace App\src\Repositories;


use App\src\Models\Address;

class AddressRepository
{
    protected $address;

    /**
     * AddressRepository constructor.
     * @param $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * @param $address
     * @return mixed
     * Создать новый адрес
     */
    public function create($address)
    {
        return $this->address->create($address);
    }


}