<?php
/**
 * Class and Function List:
 * Function list:
 * - onConstruct()
 * - createNewPost()
 * - editPost()
 * - publishPost()
 * - unpublishPost()
 * Classes list:
 * - Posts extends \
 */
namespace Multiple\Backend\Models;

class Posts extends \Phalcon\Mvc\Model {

    public function onConstruct() {
        $this->hasOne("categorie_id", "Multiple\Backend\Models\Categories", "categorie_id");
        $this->hasOne("user_id", "Multiple\Backend\Models\Users", "post_author");
        $this->hasOne("user_id", "Multiple\Backend\Models\Users", "post_editor");
    }

    public function createNewPost() {
    }

    public function editPost() {
    }

    public function publishPost() {
    }

    public function unpublishPost() {
    }
}
