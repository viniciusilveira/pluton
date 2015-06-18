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
use Multiple\Backend\Models\Users AS Users,  \Phalcon\Session\Adapter\Files as Session;

class LoginController extends SetupController {
	
	public function indexAction() {
		
		session_start();
		if ($_SESSION['user_login'] != NULL) {
			$this->dispatcher->forward(array("controller" => 'settings', "action" => 'index'));
		}
		else {
			$this->view->render('login', 'index');
		}
	}
	
	/**
	 * Efetua o login no sistema
	 * @return json_encode array para o jquery
	 */
	public function loginAction() {
		
		$this->view->disable();
		
		$users       = new Users();
		
		$user_login  = $this->request->getPost('user_login');
		$user_passwd = md5($this->request->getPost('user_passwd'));
		$success     = $users::findFirst("(user_login = '$user_login' OR user_email = '$user_login') AND user_passwd = '$user_passwd'");
		if ($success) {
			$this->creatSession($user_login);
			$data['success'] = true;
		} 
		else {
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
	public function creatSession($user_login) {
		session_start();
		
		$_SESSION['user_login'] = $user_login;
	}
	
	/**
	 * Destroi a sessão e redireciona para tela de login
	 * @return
	 */
	public function logoff() {
		session_destroy();
		$this->view->render('login', 'index');
	}
}
