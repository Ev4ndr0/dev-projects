<?php

class Categoria {
    private $id = 0;
    private $nome = '';

    public function __construct()
    {   
            
    }


    public function getId() : int {
         return $this->id;
        }
    public function setId(int $id) {
         $this->id = $id;
        }

    public function getNome() : string {
         return $this->nome;
        }
    public function setNome(string $nome){
        $this->nome = $nome;
    }

/**
 * Retorna a lista de categorias cadastradas no banco de dados da aplicação 
 * @param resource $conexao     Recurso de Conexão com a base de dados MySQL
 * @return array                Array associativo com as informações das categorias
 */
public static function listarCategoria()
{
    $sql = "SELECT * FROM categorias ORDER BY nome ASC";
    
    $db = new DB();
    $lista_categorias = $db->query($sql);
    $todas_categorias = array();

    if($lista_categorias){
        foreach($lista_categorias as $dado_categoria){
            $categoria = new Categoria();
            $categoria->setId((int)$dado_categoria['id']);
            $categoria->setNome($dado_categoria['nome']);
            array_push($todas_categorias, $categoria);
        }        
    }
    return $todas_categorias;
}

function alterarCategoria()
{ }
/**
 * Salva as informações da categoria na base de dados (INSERE E ATUALIZA)
 * @param resource $conexao     Recurso de conexão com a base de dados MySQL
 * @param array $dados_produto  Array associativo com as informações da categoria
 * @return bool                 Retorna TRUE se a categoria foi salva, FALSE caso contrário
 */
public function salvarCategoria()
{
    $this->validarCategoria();

    $db = new DB();

    $this->nome =  $db->protegerString($this->nome);

    if ($this->id > 0) {
        $sql = sprintf(
            "UPDATE categorias SET nome = '%s' WHERE id = %d", 
            $this->nome,
            $this->id
        );
    } else {
        $sql = sprintf(
            "INSERT INTO categorias(nome) VALUES('%s')",
            $this->nome
        );
    }
    return $db->executar($sql);
}

/**
 * Remove uma categoria da base de dados com base no seu ID
 * @param resource $conexao     Recurso de conexção com a base de dados MySQL
 * @param int $id               ID da categoria na base de dados
 * @return bool                 Retorna TRUE se a categoria foi excluída e FALSE se houver erro
 */
public static function removerCategoria(int $id)
{
    $sql = "DELETE FROM categorias WHERE id = $id";
    $db = new DB();

    return $db->executar($sql);
}

/**
 * Retorna as informações da categoria para o ID infornado
 * @param resource $conexao     Recurso de conexão com a base de dados MySQL
 * @param int $d                ID da categoria
 * @return array                Retorna um array associatvo com as informações da Categoria
 */
public static function getCategoriaPorId(int $id)
{
    $sql = "SELECT * FROM categorias WHERE id = $id";
    $db = new DB();
    $dado_categoria = $db->query($sql, true); // me traz um dado
    $categoria = new Categoria();

    if($dado_categoria){
        $categoria->setId((int)$dado_categoria['id']);
        $categoria->setNome($dado_categoria['nome']);
    }

    return $categoria;
}

    /**
     * Valida os dados obrigatórios para alteração ou cadastro da categoria
     * @param array $dados_categoria      Array associativo contendo as informações da categoria
     * @return void
     */
    private function validarCategoria()
    {
        if (empty(trim($this->nome)))
            throw new Exception('Nome da Categoria é obrigatório!');
    }        
}