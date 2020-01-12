<?php

class Usuario {
    private $id = 0;
    private $login = '';
    private $senha = '';

    public function __construct(){}

    public function getId() : int {
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getLogin() : string {
        return $this->login;
    }

    public function setLogin(string $login){
        $this->login = $login;
    }

    public function getSenha() : string {
        return $this->senha;
    }

    public function setSenha(string $senha){
        $this->senha = $senha;
    }


    /**
 * Valida o login e a senha do usuário na base de dados. Guarda as informações numa session se o login for bem sucedido ou lança um erro se houver algum problema
 * @return void
 */
    public function logarUsuario()
    {
        $db = new DB();
        $usuario = $db->protegerString($this->login);
        $senha = md5($db->protegerString($this->senha));
       
        $sql = sprintf("SELECT id, usuario FROM usuarios WHERE usuario = '%s' AND senha = '%s'", $usuario, $senha);
        $usuario = $db->query($sql);
        if($usuario){
            # Guarda o usuário na SESSÃO do PHP
            $_SESSION['usuario_logado'] = $usuario;
    
        }
        else {
            throw new Exception('Usuário/Senha inválido(s)!');
        }
    }
    
    /**
     * Realiza a alteração da senha do Usuário informado no banco de dados
     *  @param resource $conexao
     *  @param string $senhaNova        Nova Senha do Usuário já em md5
     *  @param int $idUsuario           ID do Usuário a ser alterado
     *  @return bool                    TRUE se a senha foi alterada, FALSE caso contrario
     */
    
    public static function alterarSenha(string $senhaNova, $idUsuario)
    {
        $db = new DB();
        $senhaNova = md5($senhaNova);
        $sql = sprintf(
            "UPDATE usuarios SET senha = '%s' WHERE id = %d",
            $senhaNova,
            $idUsuario
        );
        return $db->executar($sql);
    }
    
    
    /**
     * Retorna o nome do usuário logado na aplicação 
     * @return string       Nome do usuário logado 
     */
    public static function getUsuarioLogado(){
        return $_SESSION['usuario_logado'][0]['usuario'];
    }
    
    /**
     * Retorna o id do usuário logado na aplicação 
     * @return string       id do usuário logado 
     */
    public static function getIdUsuarioLogado(){
        return $_SESSION['usuario_logado']['id'];
    }
    
    
    /**
     * Verifica se há algum usuário logado na sessão
     * @return bool     TRUE se a session tem um usuário, FALSE caso contrário
     */
    public static function isUsuarioLogado(){
        return isset($_SESSION['usuario_logado']) and !empty($_SESSION['usuario_logado']);
    }
    /**
     * Desloga o usuário da aplicação
     * @return void
     */
    public static function logout(){
        unset($_SESSION['usuario_logado']); #destroi informação específica da sessão
        # session_destroy(); #destroi todas as sessões do browser atual
    }
    /**
     * Valida o acesso dos usuários às páginas internas da aplicação 
     * @return void
     */
    public static function validarAcesso(){
        if (!self::isUsuarioLogado()){
            header('location: index.php?acessoNegado=true');
            exit;
        }
    }

    /**
     *  Inicializa o uso de sessões no PHP
     *  @return void
     */
    public static function inicializarSessao()
    {
        session_start();
    }

}

