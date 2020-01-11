<?php

    class LivroFisico extends Livro {
        public function getTaxaImposto()
        {
            return 10;
        }

        public function getTipoLivro()
        {
            return 'livro-fisico';
        }
    }