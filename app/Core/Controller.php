<?php
abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layouts/header.php';
        require $viewFile;
        require __DIR__ . '/../Views/layouts/footer.php';
    }
    protected function redirect(string $path): never
    { header('Location: ' . $path); exit; }
    protected function post(string $key, mixed $default = ''): mixed
    { return $_POST[$key] ?? $default; }
}
