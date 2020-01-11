<?php
/* Arquivo contém todas as funções necessárias para gerenciamento dos produtos da aplicação */
/**
 * Retorna a lista de produtos cadastrados no banco de dados da aplicação 
 * @param resource $conexao     Recurso de Conexão com a base de dados MySQL
 * @return array                Array associativo com as informações dos produtos
 */
function listarProdutos($conexao)
{
    $sql = "SELECT * FROM produtos ORDER BY nome ASC";
    $resultados = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultados, MYSQLI_ASSOC);
}
/**
 * Salva as informações do produto na base de dados (INSERE E ATUALIZA)
 * @param resource $conexao     Recurso de conexão com a base de dados MySQL
 * @param array $dados_produto  Array associativo com as informações do produto
 * @return bool                 Retorna TRUE se o produto foi salvo, FALSE caso contrário
 */
function salvarProduto($conexao, array $dados_produto)
{
    validarProduto($dados_produto);
    $dados_produto['nome'] = mysqli_real_escape_string($conexao, $dados_produto['nome']);
    $dados_produto['descricao'] =  mysqli_real_escape_string($conexao, $dados_produto['descricao']);


    if ($dados_produto['id'] > 0) {
        # Atualizar produto
        $sql = sprintf(
            "UPDATE produtos SET nome = '%s', preco = %2f, descricao = '%s', categoria_id = %d, usado = %d WHERE id = %d",
            $dados_produto['nome'],
            $dados_produto['preco'],
            $dados_produto['descricao'],
            $dados_produto['categoria_id'],
            $dados_produto['usado'],
            $dados_produto['id']
        );
    } else {
        #insere os dados do produto como um novo registro
        $sql = sprintf(
            "INSERT INTO produtos(nome, preco, descricao, categoria_id,usado) VALUES('%s', %.2f, '%s', %d, %d)",
            $dados_produto['nome'],
            $dados_produto['preco'],
            $dados_produto['descricao'],
            $dados_produto['categoria_id'],
            $dados_produto['usado']
        );
    }
    return mysqli_query($conexao, $sql);
}
/**
 * Remove um produto da base de dados com base no seu ID
 * @param resource $conexao     Recurso de conexção com a base de dados MySQL
 * @param int $id               ID do Produto na base de dados
 * @return bool                 Retorna TRUE se o produto foi excluído e FALSE se houver erro
 */
function removerProduto($conexao, int $id)
{
    $sql = "DELETE FROM produtos WHERE id = $id";
    return mysqli_query($conexao, $sql);
}
/**
 * Retorna as informções do produto para o ID informado
 * @param resource $conexao     Recurso de Conexão com a base de dados MySQL
 * @param int $id               ID do produto
 * @return array                Retorna um array associativo com as informações do Produto
 */
function getProdutoPorId($conexao, int $id)
{
    $sql = "SELECT * FROM produtos WHERE id = $id";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_assoc($resultado);
}
/**
 * Valida os dados obrigatórios para alteração ou cadastro do produto
 * @param array $dados_produto      Array associativo contendo as informações do produto
 * @return void
 */
function validarProduto(array $dados_produto)
{
    if (empty(trim($dados_produto['nome'])))
        throw new Exception('Nome do produto e obrigatório!');
    if (!is_float($dados_produto['preco']) or $dados_produto['preco'] < 0) 
        throw new Exception('Preço do produto tem que ser um número positivo!');
    if (empty(trim($dados_produto['descricao'])))
        throw new Exception('Descrilção do produto e obrigatório!');
    if (empty($dados_produto['categoria_id']) or !is_int($dados_produto['categoria_id'])) 
        throw new Exception('Preço do produto e obrigatório!');
}