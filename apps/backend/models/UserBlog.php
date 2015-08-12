<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - getSource()
 * - createUserBlog()
 * Classes list:
 * - UserBlog extends \
 */
namespace Multiple\Backend\Models;

class UserBlog extends \Phalcon\Mvc\Model {
    
    /**
     * Seta o nome da tabela referenciada pelo model
     * e inicia as relações entre os Models
     */
    public function initialize() {
        $this->setSource("users_blogs");
        $this->hasMany("user_id", "Multiple\Backend\Models\Users", "user_id");
        $this->hasMany("blog_id", "Multiple\Backend\Models\Blogs", "blog_id");
    }
    
    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "users_blogs";
    }
    
    public function createUserBlog($user_id, $blog_id) {
        $user_blog = new UserBlog();
        $user_blog->user_id = $user_id;
        $user_blog->blog_id = $blog_id;
        $success = $user_blog->save();
        
        return $success;
    }
}
