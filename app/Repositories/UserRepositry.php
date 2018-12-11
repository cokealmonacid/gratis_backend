<?php
/**
 * Created by PhpStorm.
 * User: entropia
 * Date: 12/10/18
 * Time: 3:33 PM
 */

namespace App\Repositories;
use Exception;
use App\Models\User;
use App\Models\User_Rol;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UserRepository implements UserRepositoryInterface
{

    protected $user_model;
    protected $user_rol_model;

    public function __construct(User $user, User_Rol $user_rol)
    {
        $this->user_model = $user;
        $this->user_rol_model = $user_rol;
    }

    public function all()
    {
        return $this->user_model->all();
    }

    public function create(array $data)
    {
        return $this->user_model->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->user_model->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->user_model->destroy($id);
    }

    public function find($id)
    {
        if(null == $user = $this->user_model->find($id)){
            throw new ModelNotFoundException("User not found");
        }
        return $user;

    }

    /**
     * Retorna un usuario de categoria "user", user es el usuario común de perfil id 1
     * @param $email
     * @return $user
     */
    public function getUserWithMailRol ($email,$rol)
    {
        if(!$user = $this->user_model->where('email', $email)->first()){
            throw new Exception("This email account does not exist");
        }
        $match_these = ['user_id' => $user->id, 'rol_id' => $rol];
        if ($this->user_rol_model->where($match_these)->first()){
            throw new Exception("This email account does not exist");

        }
        return $user;
    }
}