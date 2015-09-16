<?php

namespace Multiple\Frontend\Models;

class PostCategorie extends \Phalcon\Mvc\Model {

    /**
     * Seta o nome da tabela referenciada pelo model
     */
    public function initialize() {
        $this->setSource("post_categorie");
        $this->HasMany("post_id", "Multiple\Frontend\Models\Posts", "post_id", array(
            'alias' => 'posts'
        ));
        $this->hasMany("categorie_id", "Multiple\Frontend\Models\Categories", "categorie_id", array(
            'alias' => 'categories'
        ));
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "post_categorie";
    }
}