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


    public function getById(int $userId): User
    {
        return $this->user->find($userId);
    }

    public function getByEmail(string $email): User
    {
        return $this->user
            ->where('email', $email)
            ->first();
    }

    public function getUserRole($userId): Role
    {
        return $this->user->find($userId)->role;
    }

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