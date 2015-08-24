<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* Classes list:
* - AnalyticsController extends BaseController
*/

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Analytics;

/**
 * Classe responsável por manipular os dados do google analytics
 */
class AnalyticsController extends BaseController {

	public function initialize(){
		$this->hasOne("blog_id", "Multiple\Backend\Models\Blogs", "blog_id", array(
            'alias' => "blogs"
        ));
	}

	public function onConstruct(){
		$analytics = Analytics::findFirst();
		$ga = new gapi($analytics->analytics_login, $analytics->analytics_password);
	}

	public function indexAction(){

	}

	public function registerAnalytics(){

	}

	public function getUsersOnline(){

	}

	public function getAccessPerPeriod(){

	}

	public function getLocationAccess(){

	}



}
