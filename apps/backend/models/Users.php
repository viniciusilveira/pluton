<?php

/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - verifyUsersExistAction()
 * - createUser()
 * - getUser()
 * - userExists()
 * Classes list:
 * - Users extends \
 */
namespace Multiple\Backend\Models;

use \Phalcon\Mvc\Model\Query;
use \Phalcon\Mvc\Model\Resultset;

/**
 * Class Users
 * @package Multiple\Backend\Models
 */
class Users extends \Phalcon\Mvc\Model
{

    public function initialize() {

        $this->hasOne("user_type_id", "Multiple\Backend\Models\UserType", "user_type_id", array('alias' => "user_type"));
        $this->hasOne("blog_id", "Multiple\Backend\Models\UserBlogs", "blog_id", array('alias' => 'blogs'));
    }

    /**
     * Verifica se existe usuários criado no banco de dados
     * @return bool true caso exista, false caso não exista nenhum
     */
    public function verifyUsersExistAction() {

        /**
         * @todo: verificar outra maneira para contar os usuários,
         * pois dá fatal error quando a tabela não existe.
         */
        try {
            $total_users = Users::count();
            return $total_users > 0 ? true : false;
        } catch(PDO\Exception $e){
            return false;
        }
    }

    /**
     * Cria um novo usuário no banco de dados
     * @param  string $user_name   Nome do Usuário
     * @param  string $user_email  Email do Usuário
     * @param  string $user_login  Login de acesso do Usuário
     * @param  string $user_passwd Senha Criptografada do Usuário
     * @param  string $user_type   Nível de acesso do Usuário (Informar aqui os níveis existentes)
     * @param  string $user_img    Nome da imagem de perfil do usuário salva no servidor (Seguir o padrão login.jpeg)
     * @param  int    $user_blog   Id do blog de acesso do usuário
     * @return bool   $success     true caso o usuário seja criado, ou false caso ocorra algum erro.
     */
    public function createUser($user_name, $user_email, $user_login, $user_passwd, $user_type_id, $user_img = NULL, $user_blog = NULL) {
        $user = new Users();
        $user->user_name = $user_name;
        $user->user_email = $user_email;
        $user->user_login = $user_login;
        $user->user_passwd = $user_passwd;
        $user->user_type_id = $user_type_id;
        $user->user_active = 1;
        if (!empty($user_blog)) $user->user_blog = $user_blog;
        if (!empty($user_img)) $user->user_img = $user_img;

        $success = $user->save();

        return $success;
    }

    /**
     * Atualiza os dados de um usuário
     * @param  int    $user_id     id do Usuário
     * @param  string $user_name   Nome do Usuário
     * @param  string $user_email  Email do Usuário
     * @param  string $user_login  Login de acesso do Usuário
     * @param  string $user_passwd Senha Criptografada do Usuário
     * @param  string $user_type   Nível de acesso do Usuário (Informar aqui os níveis existentes)
     * @param  string $user_img    Nome da imagem de perfil do usuário salva no servidor (Seguir o padrão login.jpeg)
     * @param  int    $user_blog   Id do blog de acesso do usuário
     * @return bool   $success     true caso o usuário seja criado, ou false caso ocorra algum erro.
     */
    public function updateUser($user_id, $user_name, $user_email, $user_login, $user_passwd, $user_type_id, $user_img = NULL, $user_blog = NULL) {
        $user = Users::findFirstByUser_id($user_id);
        $user->user_name = $user_name;
        $user->user_email = $user_email;
        $user->user_login = $user_login;
        $user->user_type_id = intval($user_type_id);

        if (!empty($user_passwd)) $user->user_passwd = $user_passwd;
        if (!empty($user_blog)) $user->user_blog = $user_blog;
        if (!empty($user_img)) $user->user_img = $user_img;

        $success = $user->save();

        return $success;
    }

    public function ActiveOrdeactiveUser($user_id) {
        $user = Users::findFirstByUser_id($user_id);
        $user->user_active = !$user->user_active ? 1 : 0;
        $success = $user->save();

        return $success;
    }

    /**
     * Busca um usuário pelo login/email do mesmo
     * @param  string $user_login login do usuário
     * @return objeto Users   objeto do tipo Users contendo os dados do usuário encontrado no banco de dados
     */
    public function getUser($user_login) {

        $user = Users::query()->where("user_login = :user_login:")->orWhere("user_email = :user_login:")->bind(array("user_login" => $user_login))->execute();

        return $user->getFirst();
    }

    /**
     * Verifica se existe usuário cadastrado com o login ou senha informados
     * @param  string $user_login
     * @param  string $user_email
     * @return boolean true caso usuário exista ou false caso contrario
     */
    public function userExists($user_name, $user_login, $user_email) {
        $user = Users::query()->where("user_name = :user_name:")->orWhere("user_login = :user_login:")->orWhere("user_email = :user_email:")->bind(array("user_name" => $user_name, "user_login" => $user_login, "user_email" => $user_email))->execute();
        $result = $user->getFirst();
        return !empty($result);
    }
}
