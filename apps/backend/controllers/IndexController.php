<?php

namespace Multiple\Backend\Controllers;

class IndexController extends \Phalcon\Mvc\Controller {

    public function indexAction() {
        $setup = new SetupController();
        $database = $setup->verifyDataBaseAction();
        /**
         * Verifica para qual pagina deve ser redirecionado o acesso de acordo com
         * os dados que estão faltando no banco de dados.
         * Caso ainda não exista o arquivo de conexão com o banco de dados, redireciona
         * para o formulário para criar o arquivo com os dados recebidos.
         * Caso não exista nenhum usuário redireciona para tela de criação de usuários.
         * Caso ambos estejam OK, redireciona para tela de login.
         */
        switch ($database){
            case 'file':
                $this->view->render('setup', 'index');
                break;
            case 'blog':
                $this->view->render('setup', 'newBlog');
                break;
            case 'user':
                $this->view->render('setup', 'newUser');
                break;
            case 'ok':
                $this->view->render('login', 'index');
                break;
        }
    }
}
