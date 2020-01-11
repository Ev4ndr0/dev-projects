<?php 
require_once('php/config.php');
Usuario::validarAcesso();


$msg = array();


try
{
    if($_POST) {

        $novaSenha = trim($_POST['novaSenha']);
        $ConfirmarNovaSenha = trim($_POST['confirmarSenha']);

        if(empty($novaSenha) || empty($ConfirmarNovaSenha) ){
            throw new Exception("Nova senha e Confirmação são obrigatórias!");
        }

        if($novaSenha != $ConfirmarNovaSenha ){
            throw new Exception("Nova senha e Confirmação precisam ser iguais!");
        }

        
        $id_usuario_logado = Usuario::getIdUsuarioLogado();
        if(Usuario::alterarSenha($novaSenha, $id_usuario_logado)){
            $msg = array(
                'estilo'=> 'alert alert-success',
                'mensagem'=> 'Senha alterada com sucesso!'
            );
        }
    }
}
 catch(Exception $e)
 {
    $msg = array(
        'estilo'=> 'alert alert-danger',
        'mensagem'=> $e->getMessage()
    );
 }

$titulo_pagina = 'Mudar Senha';

require_once('php/includes/cabecalho.php');
?>

<h1>Alterar Senha de Acesso</h1>

<?php include_once('php/includes/mensagem.php'); ?>

<form action="mudar-senha.php" method="post">
    <div class="form-group">
        <label>Nova Senha:</label>
        <input type="password" class="form-control" name="novaSenha">
    </div>
    <div class="form-group">
        <label>Confirmar Senha:</label>
        <input type="password" class="form-control" name="confirmarSenha">
    </div>
    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
</form>


<?php require_once('php/includes/rodape.php'); ?>