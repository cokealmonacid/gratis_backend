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
    public function findWithMailRol (String $email, String $rol);

    public function addUser(array $user_data,  String $rol);

    public function findFirstWithAtribute(String $atribute, $value);

    public function addUserLikePost($user_id, $post_id);
}