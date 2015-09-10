<?php

namespace Multiple\Backend\Models;

class Preferences extends \Phalcon\Mvc\Model{


	public function createPreferences($blog_title, $url_project = NULL){
		$preferences = new Preferences();
		$preferences->blog_id = 1;
		$preferences->preference_title_blog = $blog_title;

	}

	public function updatePreferences($blog_title, $url_project){

	}
}