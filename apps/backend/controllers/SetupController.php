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
        // view/setup/index.phtml
    }
    
    /**
     * Verifica os dados do banco para saber se existe usuário e blog já criados.
     * @return string contendo o dado não criado no banco de dados, ou 'ok' caso
     * já esteja tudo criado
     */
    public function verifyDataBaseAction(){
        echo FOLDER_PROJECT;
        if (file_exists(FOLDER_PROJECT . 'apps/config/config.ini')) {
            if(!$this->verifyBlogExistAction()){
                return 'blog';
            } elseif(!$this->verifyUserExistAction()){
                return 'user';
            } else{
                return 'ok';
            }
        } else{
            return 'file';
        }   
    }
    
    public function verifyUserExistAction(){
        $users = new \Multiple\Backend\Models\Users();
        $qtd_users = $users->count();
        if($qtd_users > 0){
            return true;
        } else{
            return false;
        }
    }
    
    public function verifyBlogExistAction(){
        $blogs = new \Multiple\Backend\Models\Blog();
        $qtd_blog = $blogs->count();
        if($qtd_blog > 0){
            return true;
        } else{
            return false;
        }
    }
    
    public function databaseConfigAction(){
        // views/setup/databaseConfig.phtml
    }
    
    public function ConfigFileCreateAction(){
        
    }
    
    public function userCreateAction(){
        $user_name = $this->request->getPost('user_name');
        $user_login = $this->request->getPost('user_login');
        $user_email = $this->request->getPost('user_email');
        $user_password = $this->request->getPost('user_password');

        $this->view->render('login', 'index');
    }
}
