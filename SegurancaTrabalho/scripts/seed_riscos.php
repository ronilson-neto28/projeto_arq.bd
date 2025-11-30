<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TipoDeRisco;
use App\Models\Risco;

$tipo = TipoDeRisco::query()->where('nome', 'Físico')->first();
if (!$tipo) {
    $tipo = TipoDeRisco::create(['nome' => 'Físico', 'cor' => '#FF0000', 'descricao' => 'Riscos físicos']);
}

$risco = Risco::query()->where('nome', 'Ruído')->first();
if (!$risco) {
    $risco = Risco::create([
        'nome' => 'Ruído',
        'tipo_risco_id' => $tipo->_id,
        'descricao' => 'Exposição a níveis elevados de pressão sonora',
    ]);
    $risco->examesObrigatorios()->create([
        'exame_id' => null,
        'periodicidade_meses' => 12,
        'obrigatorio_admissional' => true,
    ]);
}

echo json_encode(['ok' => true, 'tipo' => (string)$tipo->_id, 'risco' => (string)$risco->_id], JSON_UNESCAPED_UNICODE);

