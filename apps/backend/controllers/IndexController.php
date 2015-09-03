<?php

/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * Classes list:
 * - IndexController extends \
 */
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Controllers\SetupController;

class IndexController extends BaseController{

    /**
     * Verifica para qual pagina deve ser redirecionado o acesso de acordo com
     * os dados que estão faltando no banco de dados.
     * Caso ainda não exista o arquivo de conexão com o banco de dados, redireciona
     * para o formulário para criar o arquivo com os dados recebidos.
     * Caso não exista nenhum usuário redireciona para tela de criação de usuários.
     * Caso ambos estejam OK, redireciona para tela de login.
     */
    public function indexAction() {

        $setup = new SetupController();

        $database = SetupController::verifyInstalation();
        switch ($database) {
            case 'file':
                $dispatcher = array('controller' => 'setup', 'action' => 'index');
                break;

            case 'user':
                $dispatcher = array('controller' => 'setup', 'action' => 'install');
                break;

            case 'ok':
                $dispatcher = array('controller' => 'login', 'action' => 'index');
                break;

            case 'error':
                $dispatcher = array('controller' => 'setup', 'action' => 'error');
        }

        return $this->dispatcher->forward($dispatcher);
    }
}
