<?php

namespace Multiple\Backend\Controllers;

/**
 * Classe para conexão e configuração dos dados necessários para inicialização
 * do blog
 */
class SetupController extends \Phalcon\Mvc\Controller{
    
    public function indexAction(){
        
    }
    
    /**
     * Caso seja o primeiro acesso, ou seja não exista usuário ou blog configurado
     * no banco de dados, retorna true, caso contrário retorna false
     */
    public function verifyFirstAccess(){
        
        $users = new \Multiple\Backend\Models\Users;
        $count_users = $users->count();
        
        return $count_users == 0 ? true : false;
    }
    
    /**
     * Carrega um formulário para pegar as informações a cerca do banco de dados
     * (nome, usuário e senha)
     */
    public function databaseSettingsAction(){
        
    }
    
    /**
     * Cria um banco de dados com as informações capturadas pela action @databaseSettings
     */
    public function databaseCreateAction(){
        $database_name = $this->request->getPost('database_name');
        $database_user = $this->request->getPost('database_user');
        $database_password = $this->request->getPost('database_password');
        
        /**
         * Criar banco de dados;
         */
        $this->view->render('setup','userSettings');
    }
    
    public function userSettings(){
        
    }
    
    /**
     * Seta os valores do banco de dados do blog no módulo frontend
     */
    public function setDatabaseBlog(){
        
    }
}
