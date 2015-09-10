<?php

/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - loginAction()
 * - creatSession()
 * - logoff()
 * Classes list:
 * - LoginController extends SetupController
 */

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Users AS Users;

class LoginController extends BaseController {

    public function indexAction() {

        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $this->dispatcher->forward(array("controller" => 'dashboard', "action" => 'index'));
        } else {
            $this->view->render('login', 'index');
        }

    }

    /**
     * Efetua o login no sistema
     * @return json_encode array para o jquery
     */
    public function loginAction() {

        $this->view->disable();

        $users = new Users();

        $user_login = $this->request->getPost('user_login');
        $user_passwd = sha1(md5($this->request->getPost('user_passwd')));
        $user = $users::findFirst("(user_login = '$user_login' OR user_email = '$user_login') AND user_passwd = '$user_passwd'");
        if ($user->user_active) {
            $this->createSession($user->user_id, $user_login);
            $data['success'] = true;
        } else {
            $data['success'] = false;
            $data['message'] = 'Usuário ou senha invalido!';
        }

        echo json_encode($data);
    }

    /**
     * Inicia a sessão do usuário ao efetuar o login
     * @param  string $user_login   nome de usuário
     * @return
     */
    private function createSession($user_id, $user_login) {
        $this->session->start();
        $this->session->set("user_id", $user_id);
        $this->session->set("user_login", $user_login);
    }

    /**
     * Destroi a sessão e redireciona para tela de login
     * @return
     */
    public function logoffAction() {
        $this->session->start();
        $this->session->remove("user_id");
        $this->session->remove("user_login");
        $this->session->destroy();
        $this->view->render('login', 'index');
    }

}
