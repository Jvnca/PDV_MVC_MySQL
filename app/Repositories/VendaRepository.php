<?php
class VendaRepository
{
    public function __construct(private PDO $db) {}
    public function all(string $inicio='',string $fim=''): array
    {
        $sql='SELECT v.*, c.nome cliente_nome FROM vendas v LEFT JOIN clientes c ON c.id=v.cliente_id WHERE 1=1'; $p=[];
        if($inicio!==''){ $sql.=' AND DATE(v.criado_em)>=?'; $p[]=$inicio; }
        if($fim!==''){ $sql.=' AND DATE(v.criado_em)<=?'; $p[]=$fim; }
        $sql.=' ORDER BY v.criado_em DESC'; $st=$this->db->prepare($sql); $st->execute($p); return $st->fetchAll();
    }
    public function find(int $id): ?array
    { $st=$this->db->prepare('SELECT v.*,c.nome cliente_nome FROM vendas v LEFT JOIN clientes c ON c.id=v.cliente_id WHERE v.id=?'); $st->execute([$id]); return $st->fetch() ?: null; }
    public function items(int $id): array
    { $st=$this->db->prepare('SELECT * FROM venda_itens WHERE venda_id=?'); $st->execute([$id]); return $st->fetchAll(); }
    public function cancel(int $id): void
    {
        $this->db->beginTransaction();
        try { $st=$this->db->prepare('SELECT status FROM vendas WHERE id=? FOR UPDATE'); $st->execute([$id]); $v=$st->fetch();
            if(!$v || $v['status']==='cancelada') { $this->db->commit(); return; }
            $it=$this->db->prepare('SELECT produto_id,quantidade FROM venda_itens WHERE venda_id=?'); $it->execute([$id]);
            foreach($it->fetchAll() as $row){ $up=$this->db->prepare('UPDATE produtos SET quantidade=quantidade+? WHERE id=?'); $up->execute([$row['quantidade'],$row['produto_id']]); }
            $up=$this->db->prepare("UPDATE vendas SET status='cancelada' WHERE id=?"); $up->execute([$id]); $this->db->commit();
        } catch(Throwable $e){$this->db->rollBack(); throw $e;}
    }
}
