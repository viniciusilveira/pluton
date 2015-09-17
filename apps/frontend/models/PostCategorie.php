<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - getSource()
 * Classes list:
 * - PostCategorie extends \
 */
namespace Multiple\Frontend\Models;

class PostCategorie extends \Phalcon\Mvc\Model {

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
     * Retorna o nome da tabela ao qual a classe referencia no banco de dados
     * @return string nome da tabela
     */
    public function getSource() {
        return "post_categorie";
    }
}
