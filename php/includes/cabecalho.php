<?php
    if(!isset($titulo_pagina)){
        $titulo_pagina = 'Home';
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $titulo_pagina ?> - Loja | Caelum</title>
    <link rel="stylesheet" type="text/css" media="screen" href="_assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="_assets/css/loja.css" />
</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand">Minha Loja</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li>
                        <a href="produto-formulario.php">Cadastrar Produto</a>
                    </li>
                    <li>
                        <a href="produto-lista.php">Produtos</a>
                    </li>
                    <li>
                        <a href="categoria-formulario.php">Cadastrar Categoria</a>
                    </li>
                    <li>
                        <a href="categoria-lista.php">Categorias</a>
                    </li>
                    <li>
                        <a href="contato.php">Contato</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>  
    <main class="container">
        <article class="principal">