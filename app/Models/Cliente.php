<?php
/** Model de domínio: valida os dados básicos de um cliente antes da persistência. */
class Cliente
{
    public static function validar(array $dados): ?string
    {
        if (trim((string)($dados['nome'] ?? '')) === '') return 'O nome do cliente é obrigatório.';
        $email = trim((string)($dados['email'] ?? ''));
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) return 'Informe um e-mail válido.';
        return null;
    }
}
