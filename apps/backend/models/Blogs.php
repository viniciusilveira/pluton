<?php
/**
* Class and Function List:
* Function list:
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
    public $blog_id;
    public $bolg_name;
    public $blog_layout;

    public function verifyBlogExistAction() {
        return $this->count() > 0 ? true : false;
    }

    public function createBlog($blog_name) {
        $this->blog_name   = $blog_name;
        $this->blog_layout = 1;
        $success           = $this->create();

        return $success;
    }
}
