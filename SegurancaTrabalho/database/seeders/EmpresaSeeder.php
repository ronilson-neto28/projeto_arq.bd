<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [
            ['nome' => 'ic store',     'cnpj' => '54.446.193/0001-50'],
            ['nome' => 'anna karla',   'cnpj' => '93.404.020/0001-67'],
            ['nome' => 'pasquarelli',  'cnpj' => '63.637.755/0001-99'],
            ['nome' => 'ibm',          'cnpj' => '31.794.149/0001-02'],
            ['nome' => 'netflix',      'cnpj' => '67.374.658/0001-01'],
        ];

        foreach ($empresas as $empresa) {
            Empresa::create($empresa);
        }
    }
}
