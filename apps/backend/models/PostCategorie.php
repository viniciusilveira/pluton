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

class PostCategorie extends \Phalcon\Mvc\Model {

    /**
     * Seta o nome da tabela referenciada pelo model
     */
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
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "post_categorie";
    }

    public function createPostCategorie($post_id, $categorie_id) {
        $post_categorie = new PostCategorie();
        $post_categorie->post_id = $post_id;
        $post_categorie->categorie_id = $categorie_id;
        $success = $post_categorie->save();

        return $success;
    }

    public function deleteAllPostCategorieByPost($post_id) {
        $post_categories = PostCategorie::findByPost_id($post_id);
        foreach($post_categories as $post_categorie){
            $success = $post_categorie->delete();
            if(!$success) break;
        }

        return $success;
    }
}
