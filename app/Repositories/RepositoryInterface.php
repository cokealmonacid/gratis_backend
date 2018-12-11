<?php
/**
 * Created by PhpStorm.
 * User: entropia
 * Date: 12/10/18
 * Time: 3:28 PM
 */

namespace App\Repositories;


interface RepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);

}