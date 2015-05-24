<?php
namespace Multiple\Backend\Models;

use \Phalcon\Db\Column as Column;

/**
 * Class Users
 * @package Multiple\Backend\Models
 */
class Users extends \Phalcon\Mvc\Model
{
    
    /**
     * Verifica se existe usuários criado no banco de dados
     * @return bool true caso exista, false caso não exista nenhum
     */
    public function verifyUsersExistAction() {
        
        return $this->count() > 0 ? true : false;
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
    public function createUser($user_name, $user_email, $user_login, $user_passwd, $user_type, $user_img = NULL, $user_blog = NULL) {
        
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->user_login = $user_login;
        $this->user_passwd = $user_passwd;
        $this->user_type = $user_type;
        
        if (!empty($user_blog)) $this->user_blog = $user_blog;
        if (!empty($user_img)) $this->user_img = $user_img;
        
        $success = $this->create();
        
        return $success;
    }

    public function getUser($user_name, $user_passwd){
        $return = sers::find(array('user_name' => $user_name, 'user_passwd' => $user_passwd));
        print_r($return); die();
    }
}
