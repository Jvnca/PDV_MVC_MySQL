<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PDV - Sistema de Vendas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container"><a class="navbar-brand" href="/">PDV Faculdade</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="menu"><div class="navbar-nav">
      <a class="nav-link" href="/?r=produtos">Produtos</a><a class="nav-link" href="/?r=clientes">Clientes</a>
      <a class="nav-link" href="/?r=vendas">Carrinho / Vendas</a><a class="nav-link" href="/?r=relatorios">Relatórios</a>
    </div></div>
  </div>
</nav>
<main class="container py-4">
<?php if (!empty($_SESSION['sucesso'])): ?><div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div><?php endif; ?>
<?php if (!empty($_SESSION['erro'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div><?php endif; ?>
