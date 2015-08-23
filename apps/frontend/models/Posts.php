<?php
/**
 * Class and Function List:
 * Function list:
 * - onConstruct()
 * Classes list:
 * - Posts extends \
 */
namespace Multiple\Frontend\Models;

class Posts extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->hasOne("post_author", "Multiple\Frontend\Models\Users", "user_id", array(
            'alias' => 'author'
        ));
        $this->hasOne("post_editor", "Multiple\Frontend\Models\Users", "user_id", array(
            'alias' => 'editor'
        ));
        $this->hasOne("post_status_id", "Multiple\Frontend\Models\PostStatus", "post_status_id", array(
            'alias' => 'post_status'
        ));

        $this->HasMany("post_id", "Multiple\Frontend\Models\PostCategorie", "post_id", array(
            'alias' => 'post_categorie'
        ));
    }
}
