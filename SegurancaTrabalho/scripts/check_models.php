<?php
require __DIR__ . '/../vendor/autoload.php';

try {
    $empresa = new \App\Models\Empresa();
    $user = new \App\Models\User();
    echo "Empresa:" . get_class($empresa) . " conn=" . ($empresa->getConnectionName() ?? 'default') . "\n";
    echo "User:" . get_class($user) . " conn=" . ($user->getConnectionName() ?? 'default') . "\n";
    echo "OK";
} catch (\Throwable $e) {
    echo "ERR:" . $e->getMessage();
    exit(1);
}
