<?php
/**
* Class and Function List:
* Function list:
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
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "post_categorie";
    }

	public function createPostCategorie($post_id, $categorie_id){
		$post_categorie = new PostCategorie();
		$post_categorie->post_id = $post_id;
		$post_categorie->categorie_id = $categorie_id;
		$success = $post_categorie->save();

		return $success;
	}
}
