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

    public function verifyBlogExistAction() {
        return $this->count() > 0 ? true : false;
    }

    public function createBlog($blog_name) {
        $blog = new Blogs();
        $blog->blog_name = $blog_name;
        $blog->blog_layout = 1;
        $success = $blog->save();

        return $success;
    }
}
