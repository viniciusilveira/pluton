<?php

namespace Multiple\Backend\Controllers;

/**
 * Classe para conexão e configuração dos dados necessários para inicialização
 * do blog
 * 
 * OBSERVAÇÃO: Necessário extender a classe Injectable ao invés da Controllers para
 * ser possivel sobescrever o método __construct
 */
class SetupController extends  \Phalcon\DI\Injectable {
    
    private $users;
    private $blog;
    
    /**
     * Construct necessário para objetos
     */
    public function __construct() {
        
        $this->users = new \Multiple\Backend\Models\Users;
        $this->blog = new \Multiple\Backend\Models\Blog;
    }
    
    public function indexAction(){
        
    }
    
    /**
     * Caso seja o primeiro acesso, ou seja não exista usuário ou blog configurado
     * no banco de dados, retorna true, caso contrário retorna false
     */
    public function verifyFirstAccess(){

        $count_users = $this->users->count();
        
        return $count_users == 0 ? true : false;
    }
    
    public function userCreateAction(){
        $user_name = $this->request->getPost('user_name');
        $user_login = $this->request->getPost('user_login');
        $user_email = $this->request->getPost('user_email');
        $user_password = $this->request->getPost('user_password');

        $this->view->render('login', 'index');
    }
}
