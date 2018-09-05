<?php

namespace App\src\Services\Call;


use App\src\Repositories\CallRepository;
use App\src\Services\Util\Pagination;
use Illuminate\Support\Facades\Log;

class CallService
{
    protected $callRepository;
    protected $paginator;

    /**
     * CallService constructor.
     * @param CallRepository $callRepository
     */
    public function __construct(CallRepository $callRepository, Pagination $paginator)
    {
        $this->callRepository = $callRepository;
        $this->paginator = $paginator;
    }

    public function getAllCalls()
    {
        // Получить все звонки
    }

    /**
     * @param $call
     * Получить звонок с АТС мегафон для его последующего создания
     */
    public function receive($call)
    {
        if ($call['cmd'] == 'history') {
            $this->makeCall($call);

            Log::channel('daily')->info(serialize($call));
        }
    }

    /**
     * @param $call - с АТС Мегафон приходит следующая структура
     * cmd - тип операции, в	данном	случае history - string
     * type - тип	звонка in/out	(входящий/исходящий) - string
     * user - идентификатор пользователя облачной АТС (необходим для	сопоставления	на	стороне	CRM) - string
     * ext - внутренний	номер пользователя облачной	АТС, если есть - string
     * groupRealName - название	 отдела,	если	входящий	 звонок	 прошел	через отдел - string
     * telnum - прямой телефонный номер пользователя облачной АТС, если есть - string
     * diversion - ваш номер телефона, через который пришел входящий вызов - string
     * start - время начала	звонка в формате YYYYmmddTHHMMSSZ
     * duration - общая длительность звонка - number
     * callid - уникальный id звонка - string
     * link - запись звонка в облачной АТС - string
     * crm_token - токен приложения для проверки
     * status - success, missed (входящий звонок), success, busy, NotAvailable, NotAllowed
     */
    private function makeCall($call)
    {
        $this->callRepository->create($call);
    }


    /**
     * @return \App\src\Models\Call[]|\Illuminate\Database\Eloquent\Collection
     * Получить все звонки из АТС
     */
    public function all($page)
    {
        $calls = $this->callRepository->getAll(
            $this->paginator->itemsPerPage,
            $this->paginator->getSkippedItems($page)
        );

        return [
            'calls' => $calls,
            'pages' => ceil($this->callRepository->getPagesCount() / $this->paginator->itemsPerPage)
        ];

    }


}