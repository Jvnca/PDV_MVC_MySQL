<?php
/** Model de domínio: representa uma venda e calcula o valor dos itens recebidos. */
class Venda
{
    public static function calcularTotal(array $itens): float
    {
        $total = 0.0;
        foreach ($itens as $item) $total += (float)$item['preco_unitario'] * (int)$item['quantidade'];
        return round($total, 2);
    }
}
