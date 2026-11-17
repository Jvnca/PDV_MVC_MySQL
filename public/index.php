<?php
require dirname(__DIR__) . '/bootstrap.php';
$rota=$_GET['r']??'home';
try {
 switch($rota){
  case 'produtos': $c=new ProdutoController(); if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['salvar']))$c->save(); elseif($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['excluir']))$c->delete(); elseif(isset($_GET['form']))$c->form(); else$c->index(); break;
  case 'clientes': $c=new ClienteController(); if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['salvar']))$c->save();elseif($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['excluir']))$c->delete();elseif(isset($_GET['form']))$c->form();else$c->index();break;
  case 'vendas': $c=new VendaController(); if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['adicionar']))$c->add();elseif($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['atualizar']))$c->update();elseif($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['remover']))$c->remove();elseif($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['finalizar']))$c->checkout();else$c->index();break;
  case 'relatorios': $c=new RelatorioController();if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['cancelar']))$c->cancel();elseif(isset($_GET['id']))$c->show();else$c->index();break;
  default: require dirname(__DIR__).'/app/Views/layouts/header.php'; require dirname(__DIR__).'/app/Views/home.php'; require dirname(__DIR__).'/app/Views/layouts/footer.php';
 }
} catch(Throwable $e){ http_response_code(500); echo '<h1>Erro ao carregar o sistema</h1><p>Confira a configuração do banco e o arquivo README.</p>'; }
