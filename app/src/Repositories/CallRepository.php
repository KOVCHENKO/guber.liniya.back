<?php

namespace App\src\Repositories;


use App\src\Models\Call;

class CallRepository
{
    protected $call;

    /**
     * CallRepository constructor.
     * @param Call $call
     */
    public function __construct(Call $call)
    {
        $this->call = $call;
    }

    /**
     * @param $call
     * @return mixed
     * Создать звонок
     */
    public function create($call)
    {
        $newCall = new $this->call;
        $newCall->call_id = $call['callid'];
        $newCall->link = $call['link'];
        $newCall->ats_status = $call['status'];
        $newCall->phone = $call['phone'];
        $newCall->ext = $call['ext'];
        $newCall->type = $call['type'];

        $newCall->save();

        return $newCall;
    }

    /**
     * @param $take - count - кол-во звонков
     * @param $skip - offset - пропусть кол-во элементов
     * @return Call[]|\Illuminate\Database\Eloquent\Collection
     * Получить все звонки
     */
    public function getAll($take, $skip)
    {
        return $this->call
            ->take($take)
            ->skip($skip)
            ->get();
    }

    public function getPagesCount()
    {
        return $this->call->count();
    }
}