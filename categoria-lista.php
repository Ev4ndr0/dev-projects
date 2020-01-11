<?php 
require_once('php/config.php');
Usuario::validarAcesso();
//Exclusão do Item
$msg = array();
if(isset($_GET['excluir']) and is_numeric($_GET['excluir'])){
    $id_categoria = (int)$_GET['excluir'];
    if (Categoria::removerCategoria($id_categoria)){
        $msg = array(
            'estilo' => 'alert alert-success',
            'mensagem' => 'Categoria excluído com sucesso!'
        );
    }
    else{
        $msg = array(
            'estilo' => 'alert alert-danger',
            'mensagem' => 'Não foi possível excluír a categoria!'
        );
    }
}
# Chamando o metodo estatico criado na classe Categoria
$lista_de_categorias = Categoria::listarCategoria();

# Nome da página
$titulo_pagina = 'Lista de Categorias';

# Incluindo o cabeçalho
require_once('php/includes/cabecalho.php')
?>

            <h1>Categorias</h1>

            <?php include_once('php/includes/mensagem.php'); ?>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_de_categorias as $categoria) : ?>
                    <tr>
                        <td><?= $categoria->getNome() ?></td>
                        <td>
                        <a href="categoria-lista.php?excluir=<?= $categoria->getId()?>" class="btn btn-danger">Remover</a>
                        </td>
                        <td>
                            <a href="categoria-formulario.php?alterar=<?= $categoria->getId()?>" class="btn btn-primary">Alterar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
<?php require_once('php/includes/rodape.php'); ?>