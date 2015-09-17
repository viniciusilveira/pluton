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

    public function initialize() {
        $this->setSource("users_blogs");
        $this->hasMany("user_id", "Multiple\Backend\Models\Users", "user_id");
        $this->hasMany("blog_id", "Multiple\Backend\Models\Blogs", "blog_id");
    }

    /**
     * Retorna o nome da tabela ao qual a classe referencia no banco de dados
     * @return string nome da tabela
     */
    public function getSource() {
        return "users_blogs";
    }

    /**
     * Cria uma nova instância da tabela user_blog
     * @param  int $user_id id do usuário
     * @param  int $blog_id id do blog
     * @return boolean          Verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function createUserBlog($user_id, $blog_id) {
        $user_blog = new UserBlog();
        $user_blog->user_id = $user_id;
        $user_blog->blog_id = $blog_id;
        $success = $user_blog->save();

        return $success;
    }
}
