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

    /**
     * @param $address
     * Обновтиь адрес
     */
    public function update($address)
    {
        $addressToUpdate = $this->address->find($address['address_id']);
        $addressToUpdate->district = $address['district'];
        $addressToUpdate->location = $address['location'];
    }

    /**
     * @param $id
     * Получить адрес по id
     * @return Address
     */
    public function getById(int $id): Address
    {
        return $this->address->find($id);
    }




}
