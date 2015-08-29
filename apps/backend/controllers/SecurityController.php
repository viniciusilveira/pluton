<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* Classes list:
* - AnalyticsController extends BaseController
*/

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\GoogleAccounts;

/**
 * Classe responsável por manipular os dados do google analytics
 */
class SecurityController extends BaseController {

	private $ga;

	public function onConstruct(){
		//$analytics = Analytics::findFirst();
		//$this->ga = new gapi($analytics->analytics_login, $analytics->analytics_password);
	}

	public function indexAction(){

	}

	/**
	 * Recebe os dados da conta google informados para o usuário e salva/atualiza no banco de dados
	 * @return json
	 */
	public function registerGoogleAccountsAction(){

		$g_account = $this->request->getPost('g_account');
		$password = $this->request->getPost('g_password');
		$this->view->disable();
		$data['success'] = GoogleAccounts::createGoogleAccount($g_account, $password);

		echo json_encode($data);
	}

	public function registerFacebookAccountsAction(){

	}

	public function registerTwitterAccountsAction(){

	}

	public function ConfigureEmailAction(){

	}



}
