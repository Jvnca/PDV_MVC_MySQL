<?php
class ClienteRepository
{
    public function __construct(private PDO $db) {}
    public function all(string $busca = ''): array
    {
        $sql = 'SELECT * FROM clientes';
        if ($busca !== '') { $sql .= ' WHERE nome LIKE :q OR email LIKE :q OR cpf LIKE :q'; }
        $sql .= ' ORDER BY nome'; $st = $this->db->prepare($sql);
        if ($busca !== '') $st->bindValue(':q', "%{$busca}%");
        $st->execute(); return $st->fetchAll();
    }
    public function find(int $id): ?array
    { $st=$this->db->prepare('SELECT * FROM clientes WHERE id=?'); $st->execute([$id]); return $st->fetch() ?: null; }
    public function create(array $d): void
    { $st=$this->db->prepare('INSERT INTO clientes(nome,cpf,email,telefone,endereco) VALUES(?,?,?,?,?)'); $st->execute([$d['nome'],$d['cpf'] ?: null,$d['email'] ?: null,$d['telefone'] ?: null,$d['endereco'] ?: null]); }
    public function update(int $id,array $d): void
    { $st=$this->db->prepare('UPDATE clientes SET nome=?,cpf=?,email=?,telefone=?,endereco=? WHERE id=?'); $st->execute([$d['nome'],$d['cpf'] ?: null,$d['email'] ?: null,$d['telefone'] ?: null,$d['endereco'] ?: null,$id]); }
    public function delete(int $id): void
    { $st=$this->db->prepare('DELETE FROM clientes WHERE id=?'); $st->execute([$id]); }
}
