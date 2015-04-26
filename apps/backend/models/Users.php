<?php

namespace Multiple\Backend\Models;

use \Phalcon\Db\Column as Column;

/**
 * Class Users 
* @package Multiple\Backend\Models
 */
class Users extends \Phalcon\Mvc\Model {

    /**
     * Verifica se existe usuários criado no banco de dados
     * @return bool
     */
    public function verifyUserExistAction(){

        return $this->count() > 0 ?  true : false;
    }

    /**
     * Cria um novo usuário no banco de dados
     *
     * @param $user_name
     * @param $user_email
     * @param $user_login
     * @param $user_passwd
     * @param null $user_blog
     * @return bool
     */
    public function createUser($user_name, $user_email, $user_login, $user_passwd, $user_type, $user_img = NULL, $user_blog = NULL){
        $user = new Users();

        $user->user_name = $user_name;
        $user->user_email = $user_email;
        $user->user_login = $user_login;
        $user->user_passwd = $user_passwd;
        $user->user_type = $user_type;
        $user->user_blog = $user_blog;
        $user->user_img = $user_img;

        $success = $user->create();

        return $success;
    }
    
}
