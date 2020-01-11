<?php
/* Arquivo que contém funções de propósito geral */
#descrição de documenteção abaixo -> parâmetro a ser seguido
/**
 * Função que exibe a estrutura de uma variável na tela para propósitos de debug
 * @param mixed $variavel       Variável cuja estrutura deverá ser exibida na tela
 * @return void
 */
function custom_print($variavel){
    print '<pre>';
    print_r($variavel);
    print '</pre>'; 
}