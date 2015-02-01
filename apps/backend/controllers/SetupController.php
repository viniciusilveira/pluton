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
     * no banco de dados, redireciona para pagina de configuração
     */
    public function verifyFirstAccess(){
        
        $users = new \Multiple\Backend\Models\Users;
        $count_users = $users->count();
        
        return $count_users == 0 ? true : false;
    }
}
