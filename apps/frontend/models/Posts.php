<?php

namespace Multiple\Frontend\Models;

class Posts extends \Phalcon\Mvc\Model{
    /**
     * Busca 10 postagens ordenados por id, a partir do ID informado. Caso o ID seja nulo retorna as 10 últimas postagens.
     * @param  int $id_last id da última postagem exibida no blog
     * @return
     */
    public function getPosts($id_last = NULL){
        /**
         * @todo: Buscar 10 últimas postagens a partir do id informado via parametro.
         *             Ordernar pelo id dos parametros (Seria o mesmo que ordenar pela data de criação)
         *             OU
         *             Ordernar pela data de publicação, verificar qual a melhor opção
         */
    }
}