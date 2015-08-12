<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - verifyBlogExistAction()
 * - createBlog()
 * Classes list:
 * - Blogs extends \
 */
namespace Multiple\Backend\Models;

/**
 * Class Blogs
 * @package Multiple\Backend\Models
 */
class Blogs extends \Phalcon\Mvc\Model {
    
    public function initialize() {
        $this->hasOne("layout_id", "Multiple\Backend\Models\Layouts", "layout_id");
        $this->hasMany("blog_id", "Multiple\Backend\Models\UserBlog", "blog_id");
    }
    
    public function verifyBlogExistAction() {
        return $this->count() > 0 ? true : false;
    }
    
    /**
     * Cria um blog no banco de dados com o nome informado via parametro
     * @param  string $blog_name Nome do blog a ser criado
     * @return boolean
     */
    public function createBlog($blog_name) {
        $blog = new Blogs();
        $blog->blog_name = $blog_name;
        $blog->blog_layout = 1;
        $success = $blog->save();
        
        return $success;
    }
}
