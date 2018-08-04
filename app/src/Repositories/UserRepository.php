<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\src\Repositories;


use App\src\Models\Role;
use App\src\Models\User;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * @param int $userId
     * @return User
     * Получить пользователя по id
     */
    public function getById(int $userId): User
    {
        return $this->user->find($userId);
    }

    /**
     * @param string $email
     * @return User
     * Получить пользователя по email
     */
    public function getByEmail(string $email): User
    {
        return $this->user
            ->where('email', $email)
            ->first();
    }

    /**
     * @param $userId
     * @return Role
     * Получить роль пользователя
     */
    public function getUserRole($userId): Role
    {
        return $this->user->find($userId)->role;
    }

    /**
     * @param $data
     * @return User
     * Создать пользователя\
     */
    public function create($data): User
    {
        $user = new $this->user;
        $user->email = $data->email;
        $user->name = $data->name;
        $user->password = bcrypt($data->password);
        $user->role_id = 2;
        $user->save();

        return $user;
    }

}