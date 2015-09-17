<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - getSource()
 * - createPostCategorie()
 * - deleteAllPostCategorieByPost()
 * Classes list:
 * - PostCategorie extends \
 */
namespace Multiple\Backend\Models;

/**
 * Classe responsável por manipular dados referentes a tabela post_categorie
 */
class PostCategorie extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->setSource("post_categorie");
        $this->HasMany("post_id", "Multiple\Backend\Models\Posts", "post_id", array(
            'alias' => 'posts'
        ));
        $this->hasMany("categorie_id", "Multiple\Backend\Models\Categories", "categorie_id", array(
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

    /**
     * Cria uma nova PostCategorie
     * @param  int $post_id      id do post
     * @param  int $categorie_id id da categoria
     * @return boolean               Verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function createPostCategorie($post_id, $categorie_id) {
        $post_categorie = new PostCategorie();
        $post_categorie->post_id = $post_id;
        $post_categorie->categorie_id = $categorie_id;
        $success = $post_categorie->save();

        return $success;
    }

    /**
     * Deleta todas as categoria s referentes a um determinado post
     * @param  int $post_id id da postagem
     * @return boolean          Verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function deleteAllPostCategorieByPost($post_id) {
        $post_categories = PostCategorie::findByPost_id($post_id);
        foreach ($post_categories as $post_categorie) {
            $success = $post_categorie->delete();
            if (!$success) break;
        }

        return $success;
    }
}
