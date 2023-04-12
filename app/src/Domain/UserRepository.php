<?php

namespace App\Domain;

use App\Domain\Entity\User;

interface UserRepository
{
    public function save(User $user) : void;
    public function find($id, $lockMode = null, $lockVersion = null);
    public function findAll();
}
