<?php
declare(strict_types=1);
session_start();
// Carrega variáveis locais do .env sem depender de biblioteca externa.
$envFile = __DIR__ . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        if (getenv(trim($key)) === false) putenv(trim($key) . '=' . trim($value, " \\t\""));
    }
}
require_once __DIR__ . '/config/database.php';
spl_autoload_register(function (string $class): void {
    foreach (['Core','Models','Repositories','Services','Controllers'] as $folder) {
        $file = __DIR__ . '/app/' . $folder . '/' . $class . '.php';
        if (is_file($file)) { require_once $file; return; }
    }
});
