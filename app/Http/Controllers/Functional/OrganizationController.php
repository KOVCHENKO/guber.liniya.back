<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\Http\Requests\Functional\OrganizationRequest;
use App\src\Repositories\OrganizationRepository;
use App\src\Services\Organization\OrganizationProblemsControl;
use App\src\Services\Organization\OrganizationService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    protected $organizationRepository;
    protected $organizationProblemsControl;
    protected $organizationService;

    /**
     * OrganizationController constructor.
     * @param OrganizationRepository $organizationRepository
     * @param OrganizationProblemsControl $organizationProblemsControl
     * @param OrganizationService $organizationService
     */
    public function __construct(
        OrganizationRepository $organizationRepository,
        OrganizationProblemsControl $organizationProblemsControl,
        OrganizationService $organizationService
    )
    {
        $this->organizationRepository = $organizationRepository;
        $this->organizationProblemsControl = $organizationProblemsControl;
        $this->organizationService = $organizationService;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response`
     * Все организации
     */
    public function getAll()
    {
        return response($this->organizationRepository->getAll(), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Создать новую организацию
     */
    public function create(Request $request)
    {
        return response($this->organizationRepository->create($request->all()), 200);
    }

    /**
     * @param OrganizationRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Обновить организацию
     */
    public function update(OrganizationRequest $request, $id)
    {
        return response($this->organizationRepository->update($request->all(), $id), 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        return response($this->organizationRepository->delete($id), 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить организацию по id
     */
    public function getById($id)
    {
        return response($this->organizationRepository->getById($id), 200);
    }

    /**
     * @param $organizationId - ид организации
     * @param $problemId - ид проблемы
     * @param $status - статус
     * @return \Exception|void
     */
    public function bindProblemTypeToOrganization($organizationId, $problemId, $status)
    {
        response($this->organizationProblemsControl
            ->bindProblemTypeToOrganization($organizationId, $problemId, $status), 200);
    }

    /**
     * @param $id - ид организации
     * Получить все зявки, которые пренадлежат данной организации
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getClaimsToOrganization(Request $request, $id)
    {        
        return response($this->organizationService->getClaimsToOrganization2(
            $id,
            $request->all()
        ), 200);
    }

    // public function getClaimsToOrganization(Request $request, $id)
    // {        
    //     return response($this->organizationService->getClaimsToOrganization(
    //         $id,
    //         $request->dispatchStatusFilter,
    //         $request->search,
    //         $request->page,
    //         $request->sortByData
    //     ), 200);
    // }

    /**
     * @param $id - ид организации
     * Получить все зявки, которые пренадлежат дочерним организациям
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getClaimsToChildrenOrganization(Request $request, $id)
    {
        return response($this->organizationService->getClaimsToChildrenOrganization(
            $id,
            $request->dispatchStatusFilter,
            $request->search,
            $request->page,
            $request->sortByData
        ), 200);
    }

    public function getChildOrganization($organization_id)
    {
        return response($this->organizationRepository->getChildOrganization($organization_id), 200);
    }

}