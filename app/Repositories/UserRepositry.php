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
use App\Models\Rol;
use App\Models\Post;
use App\Models\User_Post_Like;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UserRepository implements UserRepositoryInterface
{

    protected $user_model;
    protected $user_rol_model;
    protected $rol_model;
    protected $post_model;
    protected $user_post_like_model;

    public function __construct(User $user, User_Rol $user_rol, Rol $rol_model, Post $post_model, User_Post_Like $user_post_like_model)
    {
        $this->user_model = $user;
        $this->user_rol_model = $user_rol;
        $this->rol_model = $rol_model;
        $this->post_model = $post_model;
        $this->user_post_like_model = $user_post_like_model;
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
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
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

    public function getFirstWithAtribute(String $atribute, $value){

        return $user = $this->user_model->where($atribute, $value)->first();
    }

    public function getWithMailRol (String $email, String $rol_description)
    {

        if(!$user = $this->user_model->where('email', $email)->first()){
            throw new Exception("This email account does not exist");
        }

        if (!$rol = $this->rol_model->where('description', $rol_description)->first()){
            throw new Exception("This rol does not exist");

        }
        $match_these = ['user_id' => $user->id, 'rol_id' => $rol->id];
        if (!$this->user_rol_model->where($match_these)->first()){
            throw new Exception("This email account does not exist");

        }
        return $user;
    }

    public function addUser(array $user_data, String $rol_description)
    {
        $user = $this->create($user_data);
        if (!$rol = $this->rol_model->where('description', $rol_description)->first()) {
            throw new Exception("This rol does not exist");

        }
        $this->rol_model->create([
            'user_id' => $user->id,
            'rol_id' => $rol->id
        ]);
        return $user;

    }

    public function addUserLikePost($user_id, $post_id)
    {
        $_post = $this->post_model->where('id', $post_id)->first();
        if (!$_post) {
            throw new Exception('Post not exist');
        }
        $user_like_post = $this->user_post_like_model->where('post_id', $_post->id)->where('user_id',$user_id)->first();
        if ($user_like_post ){
            if ($user_like_post->delete()) return null ;
            else throw new Exception("Error in remove like post ");
        }
        else {
            $user_like_post = $this->user_post_like_model->create(
                [
                    'user_id' => $user_id,
                    'post_id' => $_post->id
                ]
            );
        }
        return $user_like_post;


    }
}