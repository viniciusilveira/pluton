<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - loginAction()
 * - creatSession()
 * Classes list:
 * - LoginController extends \
 */
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Models\Users AS Users, \Phalcon\Crypt AS Crypt;;

class LoginController extends SetupController {
	
	public function indexAction() {
		
		// view/login/index.phtml
		
		
	}
	
	/**
	 * Efetua o login no sistema
	 * @return json_encode array para o jquery
	 */
	public function loginAction() {

		$this->view->disable();

		$crypt       = new Crypt();
		$users       = new Users();

		$user_login  = $this->request->getPost('user_login');
		$user_passwd = md5($this->request->getPost('user_passwd'));
		$success     = $users::findFirst("(user_login = '$user_login' OR user_email = '$user_login') AND user_passwd = '$user_passwd'");
		if ($success) {
			//$this->creatSession($user_name);
			$data['success']             = true;
			$data['message']             = 'Login efetuado com sucesso';
		} 
		else {
			$data['success']             = false;
			$data['message']             = 'Usuário ou senha invalido!';
		}
		
		echo json_encode($data);
	}
	
	public function creatSession($user_name) {
	}
}
