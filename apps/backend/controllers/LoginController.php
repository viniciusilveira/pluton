<?php
/**
* Class and Function List:
* Function list:
* - indexAction()
* Classes list:
* - LoginController extends \
*/
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Models\Users AS Users;

class LoginController extends \Phalcon\Mvc\Controller {
	
	public function indexAction() {
		// view/login/index.phtml
	}

	/**
	 * Efetua o login no sistema
	 * @return json_encode array para o jquery
	 */
	public function loginAction(){

		$this->view->disable();

		$parameters['user_name']   = $this->request->getPost('user_name');
        $parameters['user_passwd'] = $this->request->getPost('user_passwd');
        $conditions = "user_name = :user_name: AND user_passwd = :user_passwd:";
		$result = Users::find(array($conditions, 'bind' => $parameters));
		
		/**
		 * @todo: Verificar maneira de tratar o resultado do método find usado na linha 29
		 */

		if(empty($result->toArray())){
			$data['success'] = false;
			$data['message'] = 'Usuário ou senha invalido!';
		} else{
			$this->creatSession($parameters['user_name']);
			$data['sucess'] = true;
		}

		echo json_encode($data);
	}

	public function creatSession($user_name){

	}
}
