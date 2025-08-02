<?php

namespace Database\Seeders;

use App\Models\Funcionario;
use Illuminate\Database\Seeder;

class FuncionarioSeeder extends Seeder
{
    public function run(): void
    {
        $funcionarios = [
            ['nome' => 'ronilson gomes',  'email' => 'ronilsongomes@gmail.com',         'genero' => 'masculino',  'data_nascimento' => '1981-07-07', 'empresa_id' => 4, 'cargo_id' => 3],
            ['nome' => 'carla cristiane', 'email' => 'carla@gmail.com',                 'genero' => 'feminino',   'data_nascimento' => '1987-10-09', 'empresa_id' => 3, 'cargo_id' => 8],
            ['nome' => 'caren julyane',   'email' => 'caren@gmail.com',                 'genero' => 'feminino',   'data_nascimento' => '1968-03-14', 'empresa_id' => 2, 'cargo_id' => 7],
            ['nome' => 'aline vanucci',   'email' => 'aline@gmail.com',                 'genero' => 'feminino',   'data_nascimento' => '1995-08-22', 'empresa_id' => 1, 'cargo_id' => 1],
            ['nome' => 'arthur vanucci',  'email' => 'arthur@gmail.com',                'genero' => 'masculino',  'data_nascimento' => '1986-08-13', 'empresa_id' => 2, 'cargo_id' => 3],
            ['nome' => 'danilo do carmo', 'email' => 'danilo@gmail.com',                'genero' => 'masculino',  'data_nascimento' => '1990-04-10', 'empresa_id' => 5, 'cargo_id' => 5],
            ['nome' => 'dorinha figueira','email' => 'dorinha@gmail.com',               'genero' => 'feminino',   'data_nascimento' => '1984-12-01', 'empresa_id' => 3, 'cargo_id' => 2],
            ['nome' => 'hanna victoria',  'email' => 'hanna@gmaill.com',                'genero' => 'feminino',   'data_nascimento' => '1992-11-30', 'empresa_id' => 1, 'cargo_id' => 6],
            ['nome' => 'alfredo gomes',   'email' => 'alfredo@gmail.com',               'genero' => 'masculino',  'data_nascimento' => '1978-09-18', 'empresa_id' => 4, 'cargo_id' => 9],
            ['nome' => 'claudio vanucci', 'email' => 'claudio@gmail.com',               'genero' => 'masculino',  'data_nascimento' => '1989-06-21', 'empresa_id' => 5, 'cargo_id' => 10],
        ];

        foreach ($funcionarios as $f) {
            Funcionario::create($f);
        }
    }
}
