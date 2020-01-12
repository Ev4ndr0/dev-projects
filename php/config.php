<?php

#Configuração de acesso ao banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME','loja');
define('DB_USER', 'root');
define('DB_PWD', 'W3hs1zu8v');

#Importação dos Arquivos de Funções necessários para a aplicação
require_once ('functions/utils.php');


# Registra o carregamento automático dos arquivos de classes da aplicação
spl_autoload_register(function($classe){
    $base_dir = dirname(__FILE__) . '/classes/';
    $arquivo_classe = str_replace('\\','/',$classe) . '.php';
    $caminho_completo = $base_dir . $arquivo_classe;

    if(file_exists($caminho_completo)){
        require_once($caminho_completo);
    }
});

/**
 * Inicializa o uso de sessões na aplicação
 */
Usuario::inicializarSessao();