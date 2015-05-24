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

	public function loginAction(){

		$this->view->disable();
		$arr['user_name']   = $this->request->getPost('user_name');
        $arr['user_passwd'] = $this->request->getPost('user_passwd');
		$result = Users::findFirst($arr);
		if(emtpy($result)){
			$data['success'] = false;
			$data['message'] = 'Usuário ou senha invalido!';
		} else{
			$this->creatSession($arr['user_name'], $arr['user_passwd']);
			$data['sucess'] = true;
		}

		echo json_encode($data);
	}

	public function creatSession($user_name, $dateTime){

	}
}
