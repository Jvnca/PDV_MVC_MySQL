<?php
class VendaController extends Controller
{
    private PDO $db; private ProdutoRepository $produtos;
    public function __construct(){ $this->db=Database::connection();$this->produtos=new ProdutoRepository($this->db); }
    public function index(): void { $q=trim($_GET['q']??''); $carrinhoItens=[]; foreach(($_SESSION['carrinho']??[]) as $id=>$qtd){ $produto=$this->produtos->find((int)$id); if($produto) $carrinhoItens[]=['produto'=>$produto,'quantidade'=>(int)$qtd]; } $clientes=(new ClienteRepository($this->db))->all(); $this->render('vendas/index',['produtos'=>$this->produtos->all($q),'carrinhoItens'=>$carrinhoItens,'clientes'=>$clientes]); }
    public function add(): void { $id=(int)$this->post('produto_id');$q=max(1,(int)$this->post('quantidade',1));$p=$this->produtos->find($id);
      if(!$p){$_SESSION['erro']='Produto não encontrado.';}else{$atual=(int)(($_SESSION['carrinho'][$id]??0)+$q);if($atual>(int)$p['quantidade'])$_SESSION['erro']='Quantidade maior que o estoque.';else{$_SESSION['carrinho'][$id]=$atual;$_SESSION['sucesso']='Produto adicionado ao carrinho.';}}$this->redirect('/?r=vendas'); }
    public function update(): void { foreach(($_POST['qtd']??[]) as $id=>$q){$p=$this->produtos->find((int)$id);$q=(int)$q;if($p && $q>0 && $q<=(int)$p['quantidade'])$_SESSION['carrinho'][(int)$id]=$q;elseif($q<=0)unset($_SESSION['carrinho'][(int)$id]);} $this->redirect('/?r=vendas'); }
    public function remove(): void { unset($_SESSION['carrinho'][(int)$this->post('remover', $this->post('id'))]);$this->redirect('/?r=vendas'); }
    public function checkout(): void { try{$id=(new CheckoutService($this->db))->finalizar((int)$this->post('cliente_id') ?: null,$_SESSION['carrinho']??[]);unset($_SESSION['carrinho']);$_SESSION['sucesso']="Venda #{$id} finalizada com sucesso.";}catch(Throwable $e){$_SESSION['erro']=$e->getMessage();}$this->redirect('/?r=vendas'); }
}
