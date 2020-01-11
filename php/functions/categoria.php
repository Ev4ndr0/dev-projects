<?php
/* Arquivo contém todas as funções necessárias para gerenciamento das categorias da aplicação */
/**
 * Retorna a lista de categorias cadastradas no banco de dados da aplicação 
 * @param resource $conexao     Recurso de Conexão com a base de dados MySQL
 * @return array                Array associativo com as informações das categorias
 */
function listarCategoria($conexao)
{
    $sql = "SELECT * FROM categorias ORDER BY nome ASC";
    $resultados = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultados, MYSQLI_ASSOC);
}
function alterarCategoria()
{ }
/**
 * Salva as informações da categoria na base de dados (INSERE E ATUALIZA)
 * @param resource $conexao     Recurso de conexão com a base de dados MySQL
 * @param array $dados_produto  Array associativo com as informações da categoria
 * @return bool                 Retorna TRUE se a categoria foi salva, FALSE caso contrário
 */
function salvarCategoria($conexao, array $dados_categoria)
{
    validarCategoria($dados_categoria);
    $dados_categoria['nome'] = mysqli_real_escape_string($conexao, $dados_categoria['nome']);

    if ($dados_categoria['id'] > 0) {
        $sql = sprintf(
            "UPDATE categorias SET nome = '%s' WHERE id = %d", 
            $dados_categoria['nome'],
            $dados_categoria['nome'],
            $dados_categoria['id']
        );
    } else {
        $sql = sprintf("INSERT INTI categorias(nome) VALUES('%s')",
        $dados_categoria['nome']
    );
    }
    return mysqli_query($conexao, $sql);
}
/**
 * Remove uma categoria da base de dados com base no seu ID
 * @param resource $conexao     Recurso de conexção com a base de dados MySQL
 * @param int $id               ID da categoria na base de dados
 * @return bool                 Retorna TRUE se a categoria foi excluída e FALSE se houver erro
 */
function removerCategoria($conexao, int $id)
{
    $sql = "DELETE FROM categorias WHERE id = $id";
    return mysqli_query($conexao, $sql);
}
/**
 * Retorna as informações da categoria para o ID infornado
 * @param resource $conexao     Recurso de conexão com a base de dados MySQL
 * @param int $d                ID da categoria
 * @return array                Retorna um array associatvo com as informações da Categoria
 */
function getCategoriaPorId($conexao, int $id)
{
    $sql = "SELECT * FROM categorias WHERE id = $id";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_assoc($resultado);
}
/**
 * Valida os dados obrigatórios para alteração ou cadastro da categoria
 * @param array $dados_categoria      Array associativo contendo as informações da categoria
 * @return void
 */
function validarCategoria(array $dados_categoria)
{
    if (empty(trim($dados_categoria['nome'])))
        throw new Exception('Nome da Categoria é obrigatório!');
}