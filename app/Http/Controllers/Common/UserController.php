<?php

namespace App\Http\Controllers\Common;


use App\Http\Controllers\Controller;
use App\src\Repositories\RoleRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $roleRepository;

    /**
     * UserController constructor.
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return Authenticatable
     * Возвращает залогиненного пользователя
     */
    public function getUser(): Authenticatable
    {
        $authedUser = Auth::user();
        $role = $this->roleRepository->getById($authedUser->role_id);
        $authedUser->role = $role;

        return $authedUser;
    }
}