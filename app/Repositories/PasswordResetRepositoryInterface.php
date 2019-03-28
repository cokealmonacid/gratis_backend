<?php

namespace App\Repositories;


interface PasswordResetRepositoryInterface extends RepositoryInterface
{
    public function findFirstWithAttribute(String $atribute, $value);

    public function findWithAttributes(array $data);
}