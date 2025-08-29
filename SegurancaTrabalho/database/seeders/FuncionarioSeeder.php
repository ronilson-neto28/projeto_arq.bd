<?php

namespace Database\Seeders;

use App\Models\Funcionario;
use Illuminate\Database\Seeder;

class FuncionarioSeeder extends Seeder
{
    public function run(): void
    {
        $funcionarios = [
            ['nome' => 'ronilson gomes',  'cpf' => '12345678901', 'email' => 'ronilsongomes@gmail.com',         'genero' => 'masculino',  'data_nascimento' => '1981-07-07', 'empresa_id' => 4, 'cargo_id' => 3],
            ['nome' => 'carla cristiane', 'cpf' => '23456789012', 'email' => 'carla@gmail.com',                 'genero' => 'feminino',   'data_nascimento' => '1987-10-09', 'empresa_id' => 3, 'cargo_id' => 8],
            ['nome' => 'caren julyane',   'cpf' => '34567890123', 'email' => 'caren@gmail.com',                 'genero' => 'feminino',   'data_nascimento' => '1968-03-14', 'empresa_id' => 2, 'cargo_id' => 7],
            ['nome' => 'aline vanucci',   'cpf' => '45678901234', 'email' => 'aline@gmail.com',                 'genero' => 'feminino',   'data_nascimento' => '1995-08-22', 'empresa_id' => 1, 'cargo_id' => 1],
            ['nome' => 'arthur vanucci',  'cpf' => '56789012345', 'email' => 'arthur@gmail.com',                'genero' => 'masculino',  'data_nascimento' => '1986-08-13', 'empresa_id' => 2, 'cargo_id' => 3],
            ['nome' => 'danilo do carmo', 'cpf' => '67890123456', 'email' => 'danilo@gmail.com',                'genero' => 'masculino',  'data_nascimento' => '1990-04-10', 'empresa_id' => 1, 'cargo_id' => 5],
            ['nome' => 'dorinha figueira','cpf' => '78901234567', 'email' => 'dorinha@gmail.com',               'genero' => 'feminino',   'data_nascimento' => '1984-12-01', 'empresa_id' => 3, 'cargo_id' => 2],
            ['nome' => 'hanna victoria',  'cpf' => '89012345678', 'email' => 'hanna@gmaill.com',                'genero' => 'feminino',   'data_nascimento' => '1992-11-30', 'empresa_id' => 1, 'cargo_id' => 6],
            ['nome' => 'alfredo gomes',   'cpf' => '90123456789', 'email' => 'alfredo@gmail.com',               'genero' => 'masculino',  'data_nascimento' => '1978-09-18', 'empresa_id' => 4, 'cargo_id' => 9],
            ['nome' => 'claudio vanucci', 'cpf' => '01234567890', 'email' => 'claudio@gmail.com',               'genero' => 'masculino',  'data_nascimento' => '1989-06-21', 'empresa_id' => 2, 'cargo_id' => 10],
        ];

        foreach ($funcionarios as $f) {
            Funcionario::create($f);
        }
    }
}
