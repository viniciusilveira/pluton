<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - loginAction()
 * - newCodeResetPasswordAction()
 * - setMailLibrary()
 * - sendNewPasswordAction()
 * - createSession()
 * - logoffAction()
 * Classes list:
 * - LoginController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Users AS Users;
use Multiple\Backend\Models\Blogs;
/**
 * Classe responsável pela manipulação de login e sessão do sistema
 */
class LoginController extends BaseController {

    /**
     * Caso o usuário esteja logado, carrega a página principal, caso contrário carrega a
     * página de login.
     */
    public function indexAction() {

        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $this->dispatcher->forward(array(
                "controller" => 'dashboard',
                "action" => 'index'
            ));
        }
        else {
            $this->view->render('login', 'index');
        }
    }

    /**
     * Efetua o login no sistema
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
        }
        else {
            $data['success'] = false;
            $data['message'] = 'Usuário ou senha invalido!';
        }

        echo json_encode($data);
    }

    /**
     * Carrega página para resetar senha do usuário
     */
    public function newCodeResetPasswordAction() {
        $preferences = Blogs::findFirst();
        $vars['send_mail'] = $preferences->blog_send_mail;
        $this->view->setVars($vars);

        // apps/backend/views/newCodeResetPassword


    }

    /**
     * Seta uma instância da classe Library\Mail
     */
    private function setMailLibrary() {
        $blog = Blogs::findFirst();
        return new Mail($blog->blog_mail, $blog->blog_mail_password);
    }

    /**
     * Envia uma nova senha para o email do usuário
     * @return boolean  true em caso de sucesso ou false em caso de falha
     */
    public function sendNewPasswordAction() {
        $this->view->disable();
        $email = $this->request->getPost("email");
        $user = Users::findFirstByUser_email($email);
        $libmail = setMailLibrary();
        if (!empty($user)) {
            $new_password = $this->uid(8);
            $user->user_passwd = sha1(md5($new_password));
            $user->save();
            return $libMail->sendMessage("Nova senha - Pluton", array(
                $email,
            ) , 'Olá, sua nova senha de acesso ao sistema é: ' . $new_password);
        }
    }

    /**
     * Inicia a sessão do usuário setando o id e login do mesmo em váriaveis de sessão
     * @param  int $user_id id do usuário
     * @param  string $user_login   nome do usuário
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
