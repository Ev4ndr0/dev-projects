<?php

    class Ebook extends Livro {
        public function getTaxaImposto()
        {
            return 7.5;
        }

        public function getTipoLivro()
        {
            return 'ebook';
        }
    }