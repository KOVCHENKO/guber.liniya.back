<?php

namespace App\src\Services\Call;


use App\src\Models\Call;
use App\src\Repositories\CallRepository;
use App\src\Services\Util\Pagination;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
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
     * @return mixed
     */
    public function receive($call)
    {
        Log::channel('daily')->info(serialize($call));

        if ($call['cmd'] == 'history' && $call['type'] == 'in') {
            return $this->makeCall($call);
        }
    }

    /**
     * @param $call - с АТС Мегафон приходит следующая структура
     * cmd - тип операции, в    данном    случае history - string
     * type - тип    звонка in/out    (входящий/исходящий) - string
     * user - идентификатор пользователя облачной АТС (необходим для    сопоставления    на    стороне    CRM) - string
     * ext - внутренний    номер пользователя облачной    АТС, если есть - string
     * groupRealName - название     отдела,    если    входящий     звонок     прошел    через отдел - string
     * telnum - прямой телефонный номер пользователя облачной АТС, если есть - string
     * diversion - ваш номер телефона, через который пришел входящий вызов - string
     * start - время начала    звонка в формате YYYYmmddTHHMMSSZ
     * duration - общая длительность звонка - number
     * callid - уникальный id звонка - string
     * link - запись звонка в облачной АТС - string
     * crm_token - токен приложения для проверки
     * status - success, missed (входящий звонок), success, busy, NotAvailable, NotAllowed
     * @return mixed
     */
    private function makeCall($call)
    {
        return $this->callRepository->create($call);
    }


    /**
     * @param $page
     * @param $dateFilterOptions
     * @return \App\src\Models\Call[]|\Illuminate\Database\Eloquent\Collection
     * Получить все звонки из АТС
     */
    public function all($page, $dateFilterOptions)
    {
        $calls = $this->filterAndGet(
            $this->paginator->itemsPerPage,
            $this->paginator->getSkippedItems($page),
            $dateFilterOptions
        );

        $pages = $this->pagesCount($dateFilterOptions);

        return [
            'calls' => $calls,
            'pages' => $pages
        ];

    }

    /**
     * @param $call
     * Обновить информацию о звонке - processing_status
     * @return Call
     */
    public function updateCall($call)
    {
        return $this->callRepository->update($call);
    }

    /**
     * @param $callId
     * Пометить звонок как ошибочный
     * @return
     */
    public function markCallAsFaulty($callId)
    {
        $callToUpdate = new Call();
        $callToUpdate['id'] = $callId;
        $callToUpdate['processingStatus'] = 'failed';

        return $this->callRepository->update($callToUpdate);
    }

    /**
     * @param $take
     * @param $skip
     * @param $filterOptions
     * @return mixed
     * Фильтрация по датам
     */
    public function filterAndGet($take, $skip, $filterOptions)
    {
        // Включая полностью день крайней границы
        $filterOptions['to'] = Carbon::parse($filterOptions['to'])->addDay()->format('Y-m-d');

        switch ($filterOptions['dateFilter']) {
            case 'all':
                return $this->callRepository->getAll($take, $skip);
                break;
            case 'day':
                $day = Carbon::now()->format('Y-m-d');
                return $this->callRepository->getAllForDay($take, $skip, $day);
                break;
            case 'week':
                $start = Carbon::now()->startOfWeek();
                $finish = Carbon::now()->endOfWeek();
                return $this->callRepository->getAllForPeriod($take, $skip, $start, $finish);
                break;
            case 'period':
                return $this->callRepository->getAllForPeriod(
                    $take,
                    $skip,
                    $filterOptions['from'],
                    $filterOptions['to']);
                break;
        }

        return new Exception('date filter option has not been defined');
    }

    /**
     * @param $filterOptions
     * @return Exception|float|mixed
     * Получить кол-во страниц за определенный период
     */
    private function pagesCount($filterOptions)
    {
        switch ($filterOptions['dateFilter']) {
            case 'all':
                return ceil($this->callRepository->getPagesCount() / $this->paginator->itemsPerPage);
                break;
            case 'day':
                $day = Carbon::now()->format('Y-m-d');
                return ceil($this->callRepository->getPagesCountPerDay($day) / $this->paginator->itemsPerPage);
                break;
            case 'week':
                $start = Carbon::now()->startOfWeek();
                $finish = Carbon::now()->endOfWeek();
                return ceil($this->callRepository->getPagesCountPerPeriod($start, $finish) / $this->paginator->itemsPerPage);
                break;
            case 'period':
                $start = Carbon::parse($filterOptions['from'])->format('Y-m-d');
                $finish = Carbon::parse($filterOptions['to'])->addDay()->format('Y-m-d');
                return ceil($this->callRepository->getPagesCountPerPeriod($start, $finish) / $this->paginator->itemsPerPage);
                break;
        }

        return new Exception('date filter option has not been defined');
    }


}
