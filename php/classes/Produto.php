<?php

/**
 * Modo de Acesso
 * - privado -> somente a classe em si pode ter acesso
 * - pubico -> acessível na utilização do objeto
 * - protegido -> somente a classe e suas descendentes podem ter acesso
 */

class Produto{
    # Propriedades do objeto
    private $id = 0;
    private $nome = '';
    private $preco = 0;
    private $descricao = '';
    private $categoria = null;
    private $is_usado = false;
    private $db = null;


    public function __construct()    
    {
        $this->categoria = new Categoria();
        $this->db = new DB();
    }

    public function setId(int $id){
        $this->id = $id; 
    }

    public function getId() : int {
        return $this->id;
    }

    public function setNome(string $nome){
        $this->nome = filter_var($nome, FILTER_SANITIZE_STRING); 
    }

    public function getNome() : string {
        return $this->nome;
    }

    public function setPreco(float $preco){
        $this->preco = $preco; 
    }

    public function getPreco() : float {
        return $this->preco;
    }

    public function setDescricao(string $descricao){
        $this->descricao = filter_var($descricao, FILTER_SANITIZE_STRING); 
    }

    public function getDescricao() : string {
        return $this->descricao;
    }


    public function setCategoria(Categoria $categoria){
        $this->categoria = $categoria; 
    }

    public function getCategoria() : Categoria {
        return $this->categoria;
    }

    public function setUsado(bool $is_usado){
        $this->is_usado = $is_usado; 
    }

    public function isUsado() : bool {
        return $this->is_usado;
    }

/**
 * Retorna a lista de produtos cadastrados no banco de dados da aplicação
 * @return array                Array associativo com as informações dos produtos
 */
public static function listarProdutos()
{
    $sql = "SELECT p.*, c.nome AS nome_categoria
    FROM produtos p 
    LEFT JOIN categorias AS c
    ON p.categoria_id = c.id
    ORDER BY nome ASC";
    $db = new DB();
    $lista_produtos = $db->query($sql);
    $todos_produtos = array();

    if($lista_produtos){
        foreach($lista_produtos as $dado_produto){

            $nome_categoria = $dado_produto['nome_categoria'];
            if(!$nome_categoria){
                $nome_categoria = 'SEM CATEGORIA';
            }

            $produto = new Produto();
            
            if($dado_produto['tipo_livro'] == 'ebook'){
                $produto = new Ebook();
                $produto->setIsbn((string)$dado_produto['isbn']);
            }
            else if($dado_produto['tipo_livro'] == 'livro-fisico'){
                $produto = new LivroFisico();
                $produto->setIsbn((string)$dado_produto['isbn']);
            }
            
            $categoria = new Categoria();
            $categoria->setId((int)$dado_produto['categoria_id']);
            $categoria->setNome($nome_categoria);

            
            $produto->setId((int)$dado_produto['id']);
            $produto->setNome($dado_produto['nome']);
            $produto->setPreco((float)$dado_produto['preco']);
           
            #$produto->setPreco((float)number_format($dado_produto['preco'], 2, ',', '.'));
            $produto->setDescricao($dado_produto['descricao']);
            $produto->setUsado((bool)$dado_produto['usado']);
            $produto->setCategoria($categoria);
            
            array_push($todos_produtos, $produto);
        }
    }
    return $todos_produtos;
}

/**
 * Salva as informações do produto na base de dados (INSERE E ATUALIZA)
 * @return bool                 Retorna TRUE se o produto foi salvo, FALSE caso contrário
 */
public function salvarProduto()
{
    $this->validarProduto();
    $this->nome = $this->db->protegerString($this->nome);
    $this->descricao =  $this->db->protegerString($this->descricao);

    $isbn = '';
    $tipo_livro = '';

    if($this instanceof Livro){
        $isbn = $this->getIsbn();
        $tipo_livro = $this->getTipoLivro();
    }

    if ($this->id > 0) {
        # Atualizar produto
        $sql = sprintf(
            "UPDATE produtos SET nome = '%s', preco = %2f, descricao = '%s',
             categoria_id = %d, usado = %d, isbn = '%s', tipo_livro = '%s' WHERE id = %d",
            $this->nome,
            $this->preco,
            $this->descricao,
            $this->categoria->getId(),
            $this->is_usado,
            $isbn,
            $tipo_livro,
            $this->id
   );
    } else {
        #insere os dados do produto como um novo registro
        $sql = sprintf(
            "INSERT INTO produtos(nome, preco, descricao, categoria_id,usado, isbn, tipo_livro) 
            VALUES('%s', %.2f, '%s', %d, %d, '%s','%s')",
            $this->nome,
            $this->preco,
            $this->desricao,
            $this->categoria->getId(),
            $this->is_usado,
            $isbn,
            $tipo_livro
            
        );
    }
    return $this->db->executar($sql);
}

    /**
     * Retorna as informções do produto para o ID informado
     * @param int $id               ID do produto
     * @return array                Retorna um array associativo com as informações do Produto
     */
    public static function getProdutoPorId(int $id)
    {
        $sql = "SELECT * FROM produtos WHERE id = $id";
        $db = new DB();
        $dados_produto = $db->query($sql, true);
        $produto = new Produto();

        if($dados_produto){
            $categoria = new Categoria();
            $categoria->setId((int)$dados_produto['categoria_id']);

            if($dados_produto['tipo_livro'] == 'ebook'){
                $produto = new Ebook();
                $produto->setIsbn((string)$dados_produto['isbn']);
            }
            else if($dados_produto['tipo_livro'] == 'livro-fisico'){
                $produto = new LivroFisico();
                $produto->setIsbn((string)$dados_produto['isbn']);
            }
            
            $produto->setId((int)$dados_produto['id']);
            $produto->setNome($dados_produto['nome']);
            $produto->setPreco((float)$dados_produto['preco']);
            $produto->setDescricao($dados_produto['descricao']);
            $produto->setUsado((bool)$dados_produto['usado']);
            $produto->setCategoria($categoria);
        }


        return $produto;
    }

    /**
     * Remove um produto da base de dados com base no seu ID
     * @param int $id               ID do Produto na base de dados
     * @return bool                 Retorna TRUE se o produto foi excluído e FALSE se houver erro
     */
    public static function removerProduto(int $id)
    {
        $sql = "DELETE FROM produtos WHERE id = $id";
        $db = new DB();
        return $db->executar($sql);
    }

    /**
     * Valida os dados obrigatórios para alteração ou cadastro do produto
     * @return void
     */
    private function validarProduto()
    {
        if (empty(trim($this->nome)))
            throw new Exception('Nome do produto e obrigatório!');
        if (!is_float($this->preco) or $this->preco < 0) 
            throw new Exception('Preço do produto tem que ser um número positivo!');
        if (empty(trim($this->descricao)))
            throw new Exception('Descrilção do produto e obrigatório!');
        if (empty($this->categoria->getId())) 
            throw new Exception('Preço do produto e obrigatório!');
    }

}