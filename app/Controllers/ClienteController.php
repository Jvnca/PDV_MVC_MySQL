<?php
class ClienteController extends Controller
{
    private ClienteRepository $repo;
    public function __construct(){ $this->repo=new ClienteRepository(Database::connection()); }
    public function index(): void { $this->render('clientes/index',['clientes'=>$this->repo->all(trim($_GET['q']??''))]); }
    public function form(): void { $id=(int)($_GET['id']??0); $this->render('clientes/form',['cliente'=>$id?$this->repo->find($id):null]); }
    public function save(): void
    { $id=(int)$this->post('id',0); $d=[]; foreach(['nome','cpf','email','telefone','endereco'] as $k)$d[$k]=trim((string)$this->post($k));
      $erro=Cliente::validar($d); if($erro){$_SESSION['erro']=$erro;$this->redirect('/?r=clientes'.($id?'&form=1&id='.$id:'&form=1'));}
      if($id)$this->repo->update($id,$d);else $this->repo->create($d); $_SESSION['sucesso']='Cliente salvo com sucesso.';$this->redirect('/?r=clientes'); }
    public function delete(): void { $this->repo->delete((int)$this->post('id'));$_SESSION['sucesso']='Cliente excluído.';$this->redirect('/?r=clientes'); }
}
