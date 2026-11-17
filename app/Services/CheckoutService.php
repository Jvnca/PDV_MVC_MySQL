<?php
/** Service Layer: concentra a regra de fechamento e consistência da venda. */
class CheckoutService
{
    public function __construct(private PDO $db) {}
    public function finalizar(?int $clienteId, array $carrinho): int
    {
        if(!$carrinho) throw new RuntimeException('O carrinho está vazio.');
        $this->db->beginTransaction();
        try {
            $total=0.0; $linhas=[];
            $busca=$this->db->prepare('SELECT id,nome,quantidade,preco_unitario FROM produtos WHERE id=? FOR UPDATE');
            foreach($carrinho as $id=>$q){ $busca->execute([(int)$id]); $p=$busca->fetch();
                if(!$p || $q<1 || (int)$p['quantidade']<(int)$q) throw new RuntimeException('Estoque insuficiente para um dos produtos.');
                $preco=(float)$p['preco_unitario']; $subtotal=$preco*(int)$q;
                $linhas[]=['produto'=>$p,'q'=>(int)$q,'preco'=>$preco,'subtotal'=>$subtotal];
            }
            $total = Venda::calcularTotal(array_map(fn($l) => ['preco_unitario'=>$l['preco'],'quantidade'=>$l['q']], $linhas));
            $st=$this->db->prepare("INSERT INTO vendas(cliente_id,total,status) VALUES(?,?,'concluida')"); $st->execute([$clienteId ?: null,$total]); $vendaId=(int)$this->db->lastInsertId();
            $item=$this->db->prepare('INSERT INTO venda_itens(venda_id,produto_id,nome_produto,quantidade,preco_unitario,subtotal) VALUES(?,?,?,?,?,?)');
            $estoque=$this->db->prepare('UPDATE produtos SET quantidade=quantidade-? WHERE id=?');
            foreach($linhas as $l){$item->execute([$vendaId,$l['produto']['id'],$l['produto']['nome'],$l['q'],$l['preco'],$l['subtotal']]);$estoque->execute([$l['q'],$l['produto']['id']]);}
            $this->db->commit(); return $vendaId;
        } catch(Throwable $e){$this->db->rollBack();throw $e;}
    }
}
