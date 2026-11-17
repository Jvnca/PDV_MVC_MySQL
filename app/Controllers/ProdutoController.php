<?php
class ProdutoController extends Controller
{
    private ProdutoRepository $repo;
    public function __construct() { $this->repo = new ProdutoRepository(Database::connection()); }
    public function index(): void { $this->render('produtos/index', ['produtos' => $this->repo->all(trim($_GET['q'] ?? ''))]); }
    public function form(): void { $id=(int)($_GET['id'] ?? 0); $this->render('produtos/form', ['produto'=>$id ? $this->repo->find($id) : null]); }
    public function save(): void
    {
        $id=(int)$this->post('id',0);
        $d=['cod'=>trim((string)$this->post('cod')),'nome'=>trim((string)$this->post('nome')),
            'categoria'=>trim((string)$this->post('categoria')),'validade'=>$this->post('validade') ?: null,
            'quantidade'=>(int)$this->post('quantidade',0),'preco_unitario'=>(float)$this->post('preco_unitario',0),
            'preco_pacote'=>(float)$this->post('preco_pacote',0),'imagem'=>trim((string)$this->post('imagem'))];
        if ($erro=Produto::validar($d)) {
            $_SESSION['erro']=$erro; $this->redirect('/?r=produtos&form=1'.($id?'&id='.$id:''));
        }
        if ($id) $this->repo->update($id,$d); else $this->repo->create($d);
        $_SESSION['sucesso']='Produto salvo com sucesso.'; $this->redirect('/?r=produtos');
    }
    public function delete(): void { $this->repo->delete((int)$this->post('id')); $_SESSION['sucesso']='Produto excluído.'; $this->redirect('/?r=produtos'); }
}
