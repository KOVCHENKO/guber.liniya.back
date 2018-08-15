<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Services\Role\Specialist;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    protected $specialist;

    /**
     * SpecialistController constructor.
     * @param Specialist $specialist
     */
    public function __construct(Specialist $specialist)
    {
        $this->specialist = $specialist;
    }

    /**
     * @param $organizationId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить специалистов организации по её ИД
     */
    public function getSpecialistsOfOrganization($organizationId)
    {
        return response($this->specialist->getSpecialistsOfOrganization($organizationId), 200);
    }

    /**
     * @param Request $request - специалист - логин, пароль
     * @param $organizationId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Создать специалиста
     */
    public function createSpecialist(Request $request, $organizationId) {
        return response($this->specialist->add($request->all(), $organizationId));
    }


}