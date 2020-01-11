<?php 
require_once('php/config.php');
Usuario::validarAcesso();

#variável que guarda a mensagem de sucesso ou de erro que será exibida
$msg = array();
//Exclusão do Item
if(isset($_GET['excluir']) and is_numeric($_GET['excluir'])){
    $id_produto = (int)$_GET['excluir'];
    if (Produto::removerProduto($id_produto)){
        $msg = array(
            'estilo' => 'alert alert-success',
            'mensagem' => 'Produto excluído com sucesso!'
        );
    }
    else{
        $msg = array(
            'estilo' => 'alert alert-danger',
            'mensagem' => 'Não foi possível excluír o produto!'
        );
    }
}

# Obtemos a lista de produtos da base de dados
$lista_de_produtos = Produto::listarProdutos();

$titulo_pagina = 'Lista de Produtos';
require_once('php/includes/cabecalho.php');
?>

            <h1>Produtos</h1>

            <?php include_once('php/includes/mensagem.php'); ?> 
            
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Usado?</th>
                        <th>Tipo de Livro</th>
                        <th>ISBN</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_de_produtos as $produto) : ?>
                    <tr>
                        <td><?= $produto->getNome() ?></td>
                        <td>R$ <?= $produto->getPreco() ?></td>
                        <td><?= $produto->getDescricao() ?></td>
                        <td><?= $produto->getCategoria()->getNome() ?></td>
                        <td><?= ($produto->isUsado() == 1) ? 'Sim' : 'Não' ?></td>
                        <td>
                            <?= ($produto instanceof Livro) ? $produto->getTipoLivro() : 'N/A' ?>
                        </td>
                        <td>
                            <?= ($produto instanceof Livro) ? $produto->getIsbn() : 'N/A' ?>
                        </td>
                        <td>
                            <a href="produto-lista.php?excluir=<?= $produto->getId()?>" class="btn btn-danger">Remover</a>
                        </td>
                        <td>
                            <a href="produto-formulario.php?alterar=<?= $produto->getId() ?>" class="btn btn-primary">Alterar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
<?php require_once('php/includes/rodape.php'); ?>