<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * Classes list:
 * - SettingsController extends \
 */
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Models\Users AS Users, \Phalcon\Mvc\View AS View;
class SettingsController extends \Phalcon\Mvc\Controller {
	
	public function indexAction() {
		session_start();
		$user_login  = $_SESSION['user_login'];
		$user_passwd = $_SESSION['user_passwd'];
		if (Users::findFirst("(user_login = '$user_login' OR user_email = '$user_login') AND user_passwd = '$user_passwd'")) {
			$this->view->pick('settings/index');
		} 
		else {
			$this->view->render('login', 'index');
		}
	}
}
