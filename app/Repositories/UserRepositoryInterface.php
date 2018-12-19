<?php
/**
 * Created by PhpStorm.
 * User: entropia
 * Date: 12/10/18
 * Time: 3:32 PM
 */

namespace App\Repositories;


interface UserRepositoryInterface extends RepositoryInterface
{
    public function getWithMailRol (String $email, String $rol);

    public function addUser(array $user_data,  String $rol);

    public function getFirstWithAtribute(String $atribute, $value);

    public function addUserLikePost($user_id, $post_id);
}