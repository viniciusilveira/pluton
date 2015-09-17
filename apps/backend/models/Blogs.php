<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - verifyBlogExistAction()
 * - createBlog()
 * - updateBlog()
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

    /**
     * Verifica se já existe um blog criado
     * @return boolean true caso existe ou false caso não exista.
     */
    public function verifyBlogExistAction() {
        return $this->count() > 0 ? true : false;
    }

    /**
     * Cria um blog no banco de dados com o nome informado via parametro
     * @param  string $blog_name Nome do blog a ser criado
     * @param  int $layout_id id do layout do blog
     * @return boolean            true caso sucesso ou false caso de erro.
     */
    public function createBlog($blog_name, $layout_id) {
        $blog = new Blogs();
        $blog->blog_name = $blog_name;
        $blog->blog_layout = $layout_id;
        $success = $blog->save();

        return $success;
    }

    /**
     * Atualiza os dados de preferência do blog
     * @param  string $blog_name          nome do blog
     * @param  string $blog_url           url do blog
     * @param  string $blog_mail          email principal
     * @param  string $blog_mail_password senha do email principal
     * @param  string $blog_about         pequeno texto sobre o blog
     * @return boolean                     true caso sucesso ou false caso de erro.
     */
    public function updateBlog($blog_name, $blog_url, $blog_mail, $blog_mail_password, $blog_about) {
        $blog = Blogs::findFirst();
        $blog->blog_name = $blog_name;
        $blog->blog_url = $blog_url;
        $blog->blog_mail = $blog_mail;
        $blog->blog_mail_password = $blog_mail_password;
        $blog->blog_about = $blog_about;
        return $blog->save();
    }
}
