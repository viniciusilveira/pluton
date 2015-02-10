<?php

namespace Multiple\Backend\Controllers;

class IndexController extends \Phalcon\Mvc\Controller {

    public function indexAction() {
        $setup = new SetupController();
        $setup->verifyFirstAccess() ? $this->view->render('setup', 'index') : $this->view->render('login', 'index');
    }
}
