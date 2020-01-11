<?php
require_once('php/config.php');
$msg = array();
try 
{ 
    if (isset($_GET['logout'])){
        Usuario::logout();
        header('location: index.php');
        exit;
    }
    if(isset($_GET['acessoNegado'])){
        throw new Exception('Você não tem permissão para acessar essas páginas. Faça o login!');
    }
    if ($_POST) 
    {
        //$usuario = $_POST['login'];
        //$senha = $_POST['senha'];

        $u = new Usuario();
        $u->setLogin($_POST['login']);
        $u->setSenha($_POST['senha']);
        $u->logarUsuario();
    }
} 
catch (Exception $e) {
    $msg = array(
        'estilo' => 'alert alert-danger',
        'mensagem' => $e->getMessage()
    );
}
require_once('php/includes/cabecalho.php');
?>
<?php include_once('php/includes/mensagem.php'); ?>

<?php if (Usuario::isUsuarioLogado()) : ?>

<h1>Bem-vindo!</h1>
<div class="alert alert-success">
    Olá, você está logado como <strong><?= Usuario::getUsuarioLogado() ?></strong><br>
    <a href="index.php?logout=true">Logout</a>
    &bull;
    <a href="mudar-senha.php">Alterar Senha de Acesso</a>
</div>

<?php else: ?>

<h2>Login</h2>
<form action="index.php" method="post">
    <div class="form-group">
        <label>Usuário:</label>
        <input class="form-control" type="text" name="login" />
    </div>
    <div class="form-group">
        <label>Senha:</label>
        <input class="form-control" type="password" name="senha" />
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
<?php endif; ?>

<?php require_once('php/includes/rodape.php'); ?>