<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\FacebookAccounts;
use Facebook;

class FacebookSdkController extends \Phalcon\Mvc\Controller{

	private $app_id;
	private $app_secret;

	public function onConstruct(){

	}

	public function getLikesBlog(){
		$url = "45.55.155/pluton";
		$return = file_get_contents('http://graph.facebook.com/?ids='.$url);
		$json = json_decode($retorno, false);
		echo 'Número de likes: '.$json->$url->shares;
	}

	public function getLikesPage(){

	}

	public function getCommentsPerPost($post_id){

	}

	public function getPostsPage(){

	}
}