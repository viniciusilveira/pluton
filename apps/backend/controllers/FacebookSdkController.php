<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\FacebookAccounts;
use Facebook;

class FacebookSdkController extends \Phalcon\Mvc\Controller{

	private $app_id;
	private $app_secret;

	public function onConstruct(){
		$fb_account = FacebookAccounts::findFirst();
		$this->app_id = $fb_account->facebook_account_app_id;
		$this->app_secret = $fb_account->facebook_account_app_secret;
		$this->facebook = new Facebook(array(
			'appid' => $this->app_id,
			'secret' => $this->app_secret
			'cockie' => true
		));
	}

	public function getLikesBlog(){

	}

	public function getLikesPage(){

	}

	public function getCommentsPerPost($post_id){

	}

	public function getPostsPage(){

	}
}