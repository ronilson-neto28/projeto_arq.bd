<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Encaminhamento;
use App\Models\Funcionario;
use App\Models\Empresa;
use App\Models\Cargo;
use Illuminate\Support\Facades\DB;

class EncaminhamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar funcionários, empresas e cargos existentes
        $funcionarios = Funcionario::all();
        $empresas = Empresa::all();
        $cargos = Cargo::all();

        if ($funcionarios->isEmpty() || $empresas->isEmpty() || $cargos->isEmpty()) {
            $this->command->warn('Não há funcionários, empresas ou cargos suficientes para criar encaminhamentos.');
            return;
        }

        $tiposExame = ['admissional', 'periodico', 'retorno', 'mudanca_funcao', 'demissional'];
        $status = ['agendado', 'realizado', 'cancelado'];
        $clinicas = ['Clínica São Paulo', 'Centro Médico ABC', 'Clínica Saúde Total', 'Medicina do Trabalho LTDA'];
        $medicos = ['Dr. João Silva', 'Dra. Maria Santos', 'Dr. Pedro Costa', 'Dra. Ana Oliveira'];

        // Criar 50 encaminhamentos de exemplo
        for ($i = 0; $i < 50; $i++) {
            $funcionario = $funcionarios->random();
            $empresa = $empresas->random();
            $cargo = $cargos->where('empresa_id', $empresa->id)->first() ?? $cargos->random();
            
            $dataAtendimento = fake()->dateTimeBetween('-6 months', '+3 months');
            
            Encaminhamento::create([
                'funcionario_id' => $funcionario->id,
                'empresa_id' => $empresa->id,
                'cargo_id' => $cargo->id,
                'tipo_exame' => fake()->randomElement($tiposExame),
                'data_atendimento' => $dataAtendimento->format('Y-m-d'),
                'hora_atendimento' => fake()->time('H:i'),
                'status' => fake()->randomElement($status),
                'local_clinica' => fake()->randomElement($clinicas),
                'medico_responsavel' => fake()->randomElement($medicos),
                'observacoes' => fake()->optional(0.3)->sentence(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('50 encaminhamentos criados com sucesso!');
    }
}
