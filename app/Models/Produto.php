<?php
/** Model de domínio: aplica regras simples para os dados de produto. */
class Produto
{
    public static function validar(array $dados): ?string
    {
        if (trim((string)($dados['cod'] ?? '')) === '' || trim((string)($dados['nome'] ?? '')) === '') return 'Código e nome do produto são obrigatórios.';
        if ((int)($dados['quantidade'] ?? -1) < 0) return 'O estoque não pode ser negativo.';
        if ((float)($dados['preco_unitario'] ?? -1) < 0 || (float)($dados['preco_pacote'] ?? -1) < 0) return 'Os preços não podem ser negativos.';
        return null;
    }
}
