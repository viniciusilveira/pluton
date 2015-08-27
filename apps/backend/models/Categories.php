<?php
/**
* Class and Function List:
* Function list:
* - onConstruct()
* - newCategorie()
* - getCategories()
* - deleteCategorie()
* Classes list:
* - Categories extends \
*/
namespace Multiple\Backend\Models;
use Phalcon\Mvc\Model\Resultset;

class Categories extends \Phalcon\Mvc\Model {
    public function initialize() {

    }

    public function newCategorie($categorie_name) {
        $categorie = new Categories;
        $categorie->categorie_name = $categorie_name;
        return $categorie->save();
    }

    public function getCategories($categorie_id = NULL) {

        $categorie = !empty($categorie_id) ? Categories::findFirstByCategorie_id($categorie_id) : Categories::find();

        return $categorie;
    }

}
