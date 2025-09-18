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

        // Criar encaminhamentos distribuídos ao longo de 2025 (pelo menos 1 por mês)
        $totalEncaminhamentos = 0;
        
        // Para cada mês de 2025
        for ($mes = 1; $mes <= 12; $mes++) {
            // Criar entre 3 a 8 encaminhamentos por mês
            $encaminhamentosPorMes = rand(3, 8);
            
            for ($i = 0; $i < $encaminhamentosPorMes; $i++) {
                $funcionario = $funcionarios->random();
                $empresa = $empresas->random();
                $cargo = $cargos->where('empresa_id', $empresa->id)->first() ?? $cargos->random();
                
                // Gerar data aleatória dentro do mês específico de 2025
                $diaAleatorio = rand(1, date('t', mktime(0, 0, 0, $mes, 1, 2025))); // 't' retorna o último dia do mês
                $dataAtendimento = "2025-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($diaAleatorio, 2, '0', STR_PAD_LEFT);
                
                Encaminhamento::create([
                    'funcionario_id' => $funcionario->id,
                    'empresa_id' => $empresa->id,
                    'cargo_id' => $cargo->id,
                    'tipo_exame' => fake()->randomElement($tiposExame),
                    'data_atendimento' => $dataAtendimento,
                    'hora_atendimento' => fake()->time('H:i'),
                    'status' => fake()->randomElement($status),
                    'local_clinica' => fake()->randomElement($clinicas),
                    'medico_responsavel' => fake()->randomElement($medicos),
                    'observacoes' => fake()->optional(0.3)->sentence(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $totalEncaminhamentos++;
            }
        }

        $this->command->info("$totalEncaminhamentos encaminhamentos criados com sucesso, distribuídos ao longo de 2025!");
    }
}
