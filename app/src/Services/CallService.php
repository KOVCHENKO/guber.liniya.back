<?php

namespace App\src\Services;


use GuzzleHttp\Client;

class CallService
{
    protected $client;

    /**
     * CallService constructor.
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAllCalls()
    {
        // Получить все звонки
    }


}