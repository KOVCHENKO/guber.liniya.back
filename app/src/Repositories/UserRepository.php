<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\src\Repositories;


use App\src\Models\Role;
use App\src\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param $organizationId
     * Поулчить всех специалистов организации
     * @return \Illuminate\Support\Collection
     */
    public function getSpecialistsOfOrganization(int $organizationId): Collection
    {
        return DB::table('users')
            ->join('users_organizations',
                'users_organizations.user_id', '=', 'users.id')
            ->where('users_organizations.organization_id', '=', $organizationId)
            ->select('users.*')
            ->get();
    }

    /**
     * @param $userData - данные специалиста (логин. пароль)
     * @return mixed
     */
    public function createSpecialist($userData)
    {
        $user = new $this->user;
        $user->email = $userData['email'];
        $user->name = $userData['email'];
        $user->password = bcrypt($userData['password']);
        $user->role_id = $userData['role_id'];
        $user->save();

        return $user;
    }

    /**
     * @param User $user - пользователь с ролью специалиста
     * @param $organizationId - ид организации, к которой привязывают специалиста
     */
    public function attachUserToOrganization(User $user, $organizationId)
    {
        $user->organizations()->attach($organizationId);
    }

}