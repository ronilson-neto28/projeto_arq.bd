<?php

namespace App\Services\Mongo;

use App\Models\User;

class UserMongoService
{
    public function criar(array $dados)
    {
        return User::create($dados);
    }

    public function buscarPorEmail(string $email)
    {
        return User::query()->where('email', $email)->first();
    }
}
