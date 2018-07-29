<?php

namespace App\Http\Controllers\Common;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @return Authenticatable
     * Возвращает залогиненного пользователя
     */
    public function getUser(): Authenticatable
    {
        return Auth::user();
    }
}