<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\OrganizationRepository;
use App\src\Services\Organization\OrganizationProblemsControl;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    protected $organizationRepository;
    protected $organizationProblemsControl;

    /**
     * OrganizationController constructor.
     * @param OrganizationRepository $organizationRepository
     * @param OrganizationProblemsControl $organizationProblemsControl
     */
    public function __construct(
        OrganizationRepository $organizationRepository,
        OrganizationProblemsControl $organizationProblemsControl
    )
    {
        $this->organizationRepository = $organizationRepository;
        $this->organizationProblemsControl = $organizationProblemsControl;
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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Обновить организацию
     */
    public function update(Request $request, $id)
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
        return response($this->organizationProblemsControl
            ->bindProblemTypeToOrganization($organizationId, $problemId, $status), 200);
    }

}