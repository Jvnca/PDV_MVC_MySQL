<h1><?=$produto?'Editar produto':'Novo produto'?></h1>
<form method="post" action="/?r=produtos" class="card card-body"><input type="hidden" name="id" value="<?=$produto['id']??''?>"><div class="row g-3">
<div class="col-md-4"><label class="form-label">Código *</label><input required class="form-control" name="cod" value="<?=htmlspecialchars($produto['cod']??'')?>"></div>
<div class="col-md-8"><label class="form-label">Nome *</label><input required class="form-control" name="nome" value="<?=htmlspecialchars($produto['nome']??'')?>"></div>
<div class="col-md-6"><label class="form-label">Categoria</label><input class="form-control" name="categoria" value="<?=htmlspecialchars($produto['categoria']??'')?>"></div>
<div class="col-md-6"><label class="form-label">Validade</label><input type="date" class="form-control" name="validade" value="<?=htmlspecialchars($produto['validade']??'')?>"></div>
<div class="col-md-4"><label class="form-label">Quantidade em estoque</label><input type="number" min="0" required class="form-control" name="quantidade" value="<?=$produto['quantidade']??0?>"></div>
<div class="col-md-4"><label class="form-label">Preço unitário (R$)</label><input type="number" min="0" step="0.01" required class="form-control" name="preco_unitario" value="<?=$produto['preco_unitario']??'0.00'?>"></div>
<div class="col-md-4"><label class="form-label">Preço do pacote (R$)</label><input type="number" min="0" step="0.01" required class="form-control" name="preco_pacote" value="<?=$produto['preco_pacote']??'0.00'?>"></div>
<div class="col-12"><label class="form-label">Nome do arquivo da imagem (opcional)</label><input class="form-control" name="imagem" value="<?=htmlspecialchars($produto['imagem']??'')?>"><div class="form-text">Informe apenas o nome do arquivo, se ele estiver disponível na pasta de imagens.</div></div>
</div><div class="mt-3"><button class="btn btn-success" name="salvar">Salvar</button> <a class="btn btn-secondary" href="/?r=produtos">Voltar</a></div></form>
