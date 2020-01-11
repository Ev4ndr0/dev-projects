<?php
require_once('php/config.php');
Usuario::validarAcesso();
#variável que guarda as mensagens
$msg = array();
$is_alteracao = false;

# instanciando um novo objeto categoria
$categoria = new Categoria();

try {
    # verificamos se há dados enviados via POST
    if ($_POST) {
        $categoria->setId((int)$_POST['categoria_id']);
        $categoria->setNome($_POST['nome']);

        if ($categoria->salvarCategoria()) {
            $msg = array(
                'estilo' => 'alert alert-success',
                'mensagem' => 'Categoria salva com sucesso'
            );
        } else {
            throw new Exception('Não foi possível salvar Categoria!');
        }
    }
    # Verifica se foi passado o parãmetro de alteração de categoria
    if (isset($_GET['alterar']) and is_numeric($_GET['alterar']))
     {
        $id_categoria = (int)$_GET['alterar'];
        $categoria = Categoria::getCategoriaPorId($id_categoria);
        if ($categoria->getId() == 0) {
            throw new Exception('Categoria não encontrada. Cadastre uma nova!');
        } 
        else {
            $is_alteracao = true;
        }
    }
} 
catch (Exception $e) {
    $msg = array(
        'estilo' => 'alert alert-danger',
        'mensagem' => $e->getMessage()
    );
}
$titulo_pagina = ($is_alteracao) ? 'Atualizar Categoria' : 'Adicionar Categoria';
$url_formulario = ($is_alteracao) ? '' : 'categoria-formulario.php';
require_once('php/includes/cabecalho.php');
?>
<h1><?= $titulo_pagina ?></h1>

<?php include_once('php/includes/mensagem.php'); ?>

<form action="<?= $url_formulario ?>" method="POST">
    <div class="form-group">
        <label>Nome:</label>
        <input class="form-control" type="text" name="nome" value="<?= $categoria->getNome() ?>"/>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <input type="hidden" name="categoria_id" value="<?= $categoria->getId()?>" />
</form>

<?php require_once('php/includes/rodape.php'); ?>