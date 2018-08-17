<?php

namespace App\Http\Middleware;

use App\src\Models\Role;
use App\src\Repositories\RoleRepository;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    protected $roleRepository;

    /**
     * RoleMiddleware constructor.
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param array $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {
        $authedUser = Auth::user();

        if (!empty($authedUser->role_id)) {
            $userRole = $this->roleRepository->getById($authedUser->role_id);
        }

        foreach ($role as $singleRole) {
            /** @var Role $userRole */
            if ($singleRole == $userRole->name) {
                return $next($request);
            } else {
                continue;
            }
        }

        return response('there is not enough permissions for this request', 403);
    }
}
