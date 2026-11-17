<?php
class ProdutoRepository
{
    public function __construct(private PDO $db) {}
    public function all(string $q=''): array
    { $st=$this->db->prepare('SELECT * FROM produtos WHERE nome LIKE ? OR cod LIKE ? ORDER BY nome'); $st->execute(["%$q%","%$q%"]); return $st->fetchAll(); }
    public function find(int $id): ?array
    { $st=$this->db->prepare('SELECT * FROM produtos WHERE id=?'); $st->execute([$id]); return $st->fetch() ?: null; }
    public function create(array $d): void
    { $st=$this->db->prepare('INSERT INTO produtos(cod,nome,categoria,validade,quantidade,preco_unitario,preco_pacote,imagem) VALUES(?,?,?,?,?,?,?,?)'); $st->execute([$d['cod'],$d['nome'],$d['categoria'] ?: null,$d['validade'],$d['quantidade'],$d['preco_unitario'],$d['preco_pacote'],$d['imagem'] ?: null]); }
    public function update(int $id,array $d): void
    { $st=$this->db->prepare('UPDATE produtos SET cod=?,nome=?,categoria=?,validade=?,quantidade=?,preco_unitario=?,preco_pacote=?,imagem=? WHERE id=?'); $st->execute([$d['cod'],$d['nome'],$d['categoria'] ?: null,$d['validade'],$d['quantidade'],$d['preco_unitario'],$d['preco_pacote'],$d['imagem'] ?: null,$id]); }
    public function delete(int $id): void
    { $st=$this->db->prepare('DELETE FROM produtos WHERE id=?'); $st->execute([$id]); }
}
