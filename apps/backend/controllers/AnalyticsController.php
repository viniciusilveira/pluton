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
class AnalyticsController extends BaseController {

	private $ga;

	public function onConstruct(){
		//$analytics = Analytics::findFirst();
		//$this->ga = new gapi($analytics->analytics_login, $analytics->analytics_password);
	}

	public function indexAction(){

	}

	public function registerAnalytics($g_account, $password){
		$this->view->disable();
		$data['success'] = GoogleAccounts::createGoogleAccount($g_account, $password);

		echo json_encode($data);
	}

	public function getUsersOnline(){

	}

	public function getAccessPerPeriod(){

	}

	public function getLocationAccess(){

	}



}
