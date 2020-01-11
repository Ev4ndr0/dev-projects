<?php

    abstract class Livro extends Produto{
        protected $isbn = '';

        public function getIsbn() : string {
            return $this->isbn;
        }

        public function setIsbn(string $isbn){ 
           $this->isbn = filter_var($isbn, FILTER_SANITIZE_STRING);
        }

        public abstract function getTaxaImposto();
        public abstract function getTipoLivro();

    }