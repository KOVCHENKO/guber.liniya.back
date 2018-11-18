<?php

namespace App\src\Repositories;


use App\src\Models\Call;
use Illuminate\Support\Collection;

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
        $newCall->processing_status = 'raw';
//        $newCall->created_at = $call['start'];

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
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return mixed
     * Получить кол-во страниц всего
     */
    public function getPagesCount()
    {
        return $this->call->count();
    }

    /**
     * @param $call
     * Обновить информацию о звонке
     * @return
     * возвращает обновленный звонок
     */
    public function update($call)
    {
        $callToUpdate = $this->call->find($call['id']);
        $callToUpdate->processing_status = $call['processingStatus'];
        $callToUpdate->save();

        return $callToUpdate;
    }

    /**
     * Получить все звонки за день
     * @param $take
     * @param $skip
     * @param $day
     * @return Collection
     */
    public function getAllForDay($take, $skip, $day): Collection
    {
        return $this->call
            ->take($take)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->where('created_at', 'like', '%'.$day.'%')
            ->get();
    }

    /**
     * @param $take
     * @param $skip
     * @param $start - начало
     * @param $finish - конец
     * Получить все звонки за определенный период
     * @return Collection - коллекция звонков
     */
    public function getAllForPeriod($take, $skip, $start, $finish): Collection
    {
        return $this->call
            ->take($take)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->whereBetween('created_at', [$start, $finish])
            ->get();
    }

    /**
     * @param string $day
     * @return mixed
     * Получить кол-во страниц по дням для звонков
     */
    public function getPagesCountPerDay(string $day)
    {
        return $this->call
            ->where('created_at', 'like', '%'.$day.'%')
            ->count();
    }


    /**
     * @param $start
     * @param $finish
     * @return mixed
     * Получить кол-во страниц за определенный период
     */
    public function getPagesCountPerPeriod($start, $finish)
    {
        return $this->call
            ->whereBetween('created_at', [$start, $finish])
            ->count();
    }


}
