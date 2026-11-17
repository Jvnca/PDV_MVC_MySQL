<?php
class RelatorioController extends Controller
{
    private VendaRepository $repo;
    public function __construct(){ $this->repo=new VendaRepository(Database::connection()); }
    public function index(): void { $inicio=$_GET['inicio']??'';$fim=$_GET['fim']??'';$vendas=$this->repo->all($inicio,$fim);$total=0;foreach($vendas as $v)if($v['status']==='concluida')$total+=(float)$v['total'];$this->render('relatorios/index',['vendas'=>$vendas,'inicio'=>$inicio,'fim'=>$fim,'total'=>$total]); }
    public function show(): void { $id=(int)($_GET['id']??0);$v=$this->repo->find($id);if(!$v){$_SESSION['erro']='Venda não encontrada.';$this->redirect('/?r=relatorios');}$this->render('relatorios/show',['venda'=>$v,'itens'=>$this->repo->items($id)]); }
    public function cancel(): void { try{$this->repo->cancel((int)$this->post('id'));$_SESSION['sucesso']='Venda cancelada e estoque devolvido.';}catch(Throwable $e){$_SESSION['erro']='Não foi possível cancelar a venda.';}$this->redirect('/?r=relatorios'); }
}
