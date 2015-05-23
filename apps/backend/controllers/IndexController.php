<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * Classes list:
 * - IndexController extends \
 */
namespace Multiple\Backend\Controllers;

class IndexController extends \Phalcon\Mvc\Controller {
    
    /**
     * Redireciona para a página correta de acordo com o retorno da action SetupController->verifyDataBaseAction();
     * @return
     */
    public function indexAction() {
        $setup    = new SetupController();
        $database = $setup->verifyDataBaseAction();
        switch ($database) {
            case 'connect':
                $this->view->render('setup', 'error');
                break;
            case 'user':
                $this->view->render('setup', 'newUser');
                break;
            case 'ok':
                $this->view->render('login', 'index');
                break;
            case 'file':
                $this->view->render('setup', 'index');
                break;
        }
    }
}
