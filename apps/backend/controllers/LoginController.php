<?php

namespace Multiple\Backend\Controllers;

class LoginController extends \Phalcon\Mvc\Controller {
    
    public function indexAction() {
        $this->view->render('login', 'index');
    }

}
