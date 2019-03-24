<?php

namespace App\Repositories;


interface PasswordResetRepositoryInterface extends RepositoryInterface
{
    public function findFirstWithAtribute(String $atribute, $value);
}