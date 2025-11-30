<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Empresa;
use App\Models\Cargo;
use MongoDB\BSON\ObjectId;

$empresa = Empresa::query()->first();
if (!$empresa) {
    echo "ERR: no empresa";
    exit(1);
}

$created = [];
$cargo1 = Cargo::create([
    'nome' => 'Técnico de Segurança',
    'empresa_id' => $empresa->_id,
    'cbo' => '351605',
]);
$cargo1->riscos()->create([
    'risco_id' => new ObjectId(),
    'tipo_risco_id' => new ObjectId(),
    'grau' => 3,
]);
$created[] = ['id' => (string)($cargo1->_id ?? $cargo1->id), 'nome' => $cargo1->nome];

$cargo2 = Cargo::create([
    'nome' => 'Auxiliar de Segurança',
    'empresa_id' => $empresa->_id,
    'cbo' => '351606',
]);
$cargo2->riscos()->create([
    'risco_id' => new ObjectId(),
    'tipo_risco_id' => new ObjectId(),
    'grau' => 2,
]);
$created[] = ['id' => (string)($cargo2->_id ?? $cargo2->id), 'nome' => $cargo2->nome];

echo json_encode(['ok' => true, 'empresa' => (string)$empresa->_id, 'cargos' => $created], JSON_UNESCAPED_UNICODE);

