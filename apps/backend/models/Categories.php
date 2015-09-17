<?php
/**
 * Class and Function List:
 * Function list:
 * - newCategorie()
 * - getCategories()
 * Classes list:
 * - Categories extends \
 */
namespace Multiple\Backend\Models;

/**
 * Classe responsável por manipular dados referentes as categorias
 */
class Categories extends \Phalcon\Mvc\Model {

    /**
     * Adiciona uma nova categoria
     * @param  string $categorie_name nome da nova categoria
     * @return boolean                 verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function newCategorie($categorie_name) {
        $categorie = new Categories;
        $categorie->categorie_name = $categorie_name;
        return $categorie->save();
    }

    /**
     * Retorna categorias por ID
     * @param  int $categorie_id id da categoria a ser retornada
     * @return \Phalcon\Mvc\Resultset Objeto resultset com informações sobre categories
     */
    public function getCategories($categorie_id = NULL) {

        $categorie = !empty($categorie_id) ? Categories::findFirstByCategorie_id($categorie_id) : Categories::find();

        return $categorie;
    }
}
