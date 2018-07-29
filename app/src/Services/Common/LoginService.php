<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\src\Services\Common;


use App\src\Repositories\UserRepository;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginService
{
    protected $userRepository;

    /**
     * LoginService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $data
     * @return \App\src\Models\User
     * data - contains - email, password
     * @throws Exception
     */
    public function login($data)
    {
        $user = $this->userRepository->getByEmail($data['email']);

        if(($data['password']) == $user->password) {
            return $user;
        }

        throw new Exception('user has not been found');
    }


    /**
     * @param $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Авторизация через JWT токен
     */
    public function authViaJWT($data)
    {
        if (!$token = JWTAuth::attempt($data)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }

        return response([
            'status' => 'success',
            'token' => $token
        ]);
    }

    /**
     * @param $request
     * @return \App\src\Models\User
     * Регистрация нового пользователя
     */
    public function register($request)
    {
        return $this->userRepository->create($request);
    }
}