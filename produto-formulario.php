<?php
require_once('php/config.php');
Usuario::validarAcesso();

#variável que guarda as mensagens
$msg = array();
$is_alteracao = false;

# criado novo objeto
$produto = new Produto();

# retorna a lista das categorias do banco de dados
$lista_de_categorias = Categoria::listarCategoria();
try {
    # verificamos se algo foi postado para essa página
    if ($_POST) 
    {
        if(!empty(trim($_POST['livro']))){
            $tipo_livro = $_POST['livro'];

            if($tipo_livro == 'ebook'){
                $produto = new Ebook();
            }
            else if ($tipo_livro == 'livro-fisico'){
                $produto = new LivroFisico();
            }

            if($produto instanceof Livro and !empty(trim($_POST['isbn']))){
                $produto->setIsbn($_POST['isbn']);
            }
        }


        $produto->setId((int)$_POST['produto_id']);
        $produto->setNome($_POST['nome']);
        $produto->setPreco((float) $_POST['preco']);
        $produto->setDescricao($_POST['descricao']);
        $produto->getCategoria()->setId((int)$_POST['categoria_id']);
        $produto->setUsado((isset($_POST['usado'])) ? true : false);

        if ($produto->salvarProduto()) {
            $msg = array(
                'estilo' => 'alert alert-success',
                'mensagem' => 'Produto salvo com sucesso!'
            );
        } else {
            throw new Exception('Não foi possível salvar o Produto!');
        }
    }
    # Verifica se há atualizações no produto
    if (isset($_GET['alterar']) and is_numeric($_GET['alterar'])) {
        $is_alteracao = true;
        $id_produto = (int) $_GET['alterar'];
        $produto = Produto::getProdutoPorId($id_produto);
        if ($produto->getId() == 0) {
            $is_alteracao = false;
            throw new Exception('Produto não encontrado na base de dados! Cadastre um novo!');
        }
    }
} catch (Exception $e) {
    $msg = array(
        'estilo' => 'alert alert-danger',
        'mensagem' => $e->getMessage()
    );
}
$titulo_pagina = ($is_alteracao) ? 'Atualizar Produto' : 'Cadastrar Produto';
$url_formulario = ($is_alteracao) ? '' : 'produto-formulario.php';
require_once('php/includes/cabecalho.php');
?>

<h1><?= $titulo_pagina ?></h1>

<?php include_once('php/includes/mensagem.php'); ?>

<form action="<?= $url_formulario ?>" method="post">
    <div class="form-group">
        <label>Nome:</label>
        <input class="form-control" type="text" name="nome" value="<?= $produto->getNome() ?>" />
    </div>
    <div class="form-group">
        <label>Preço:</label>
        <input class="form-control" type="number" name="preco" step=".01" value="<?= $produto->getPreco() ?>" />
    </div>
    <div class="form-group">
        <label>Descrição:</label>
        <textarea name="descricao" class="form-control"><?= $produto->getDescricao() ?></textarea>
    </div>
    <div class="form-group">
        <label>Categoria:</label>
        <select name="categoria_id" class="form-control">
            <?php foreach ($lista_de_categorias as $categoria) : ?>
                <option value="<?= $categoria->getId(); ?>" 
                <?= ($categoria->getId() == $produto->getCategoria()->getId()) ? 'selected' : '' ?>>
                    <?= $categoria->getNome(); ?>
                </option>
            <?php endforeach; ?>
        </select>

    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="usado" value="true" <?= ($produto->isUsado()) ? 'checked' : '' ?> /> Usado
        </label>
    </div>
    
        <div class="form-group">
            <label for="">É livro?</label>
            <select name="livro" class="custom-select">
                <option value="">-- Selecione o Tipo --</option>
                <option value="ebook" <?= ($produto instanceof Ebook) ? 'selected' : ''?>>E-book</option>
                <option value="livro-fisico" <?= ($produto instanceof LivroFisico) ? 'selected' : ''?>>Livro Físico</option>
            </select>
        </div>

        <div class="form-group">
            <label for="">Isbn</label>
            <input type="text" name="isbn" class="form-control" value="<?= ($produto instanceof Livro) ? $produto->getIsbn() : '' ?>">
        </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <input type="hidden" name="produto_id" value="<?= $produto->getId()?>">
</form>

<?php require_once('php/includes/rodape.php'); ?>