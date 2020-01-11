<?php
# Arquivo que contém as funções de gerenciamento de recursos para usuários da aplicação 
# Ativa o uso de sessões
session_start();
/**
 * Valida o login e a senha do usuário na base de dados. Guarda as informações numa session se o login for bem sucedido ou lança um erro se houver algum problema
 * @param resource $conexao     Recurso da Conexão com a base de dados MySQL
 * @param string $usuario       Login do usuário
 * @param string $senha         Senha do Usuário
 */
function loginUsuario($conexao, string $usuario, string $senha){
    $usuario = mysqli_real_escape_string($conexao, $usuario);
    $senha = mysqli_real_escape_string($conexao, $senha);
    $sql = sprintf("SELECT id, usuario FROM usuarios WHERE usuario = '%s' AND senha = '%s'", $usuario, $senha);
    $resultado = mysqli_query($conexao, $sql);
    $usuario = mysqli_fetch_assoc($resultado);
    if($usuario){
        #Guarda o usuário na SESSÃO do PHP
        $_SESSION['usuario_logado'] = $usuario;

    }
    else {
        throw new Exception('Usuário/Senha inválido(s)!');
    }
}

/**
 * Realiza a alteração da senha do Usuário informando
 *  @param
 *  @param
 *  @param
 *  @return bool
 */

function alterarSenha($conexao, string $senhaNova, $idUsuario)
{
    $sql = sprintf(
        "UPDATE usuarios SET senha = '%s' WHERE id = %d",
        $senhaNova,
        $idUsuario
    );
    return mysqli_query($conexao,$sql);
}


/**
 * Retorna o nome do usuário logado na aplicação 
 * @return string       Nome do usuário logado 
 */
function getUsuarioLogado(){
    return $_SESSION['usuario_logado']['usuario'];
}

/**
 * Retorna o id do usuário logado na aplicação 
 * @return string       id do usuário logado 
 */
function getIdUsuarioLogado(){
    return $_SESSION['usuario_logado']['id'];
}


/**
 * Verifica se há algum usuário logado na sessão
 * @return bool     TRUE se a session tem um usuário, FALSE caso contrário
 */
function isUsuarioLogado(){
    return isset($_SESSION['usuario_logado']) and !empty($_SESSION['usuario_logado']);
}
/**
 * Desloga o usuário da aplicação
 * @return void
 */
function logout(){
    unset($_SESSION['usuario_logado']); #destroi informação específica da sessão
    # session_destroy(); #destroi todas as sessões do browser atual
}
/**
 * Valida o acesso dos usuários às páginas internas da aplicação 
 * @return void
 */
function validarAcesso(){
    if (!isUsuarioLogado()){
        header('location: index.php?acessoNegado=true');
        exit;
    }
}