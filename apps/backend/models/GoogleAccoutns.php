<?php

namespace Multiple\Backend\Models;

class GoogleAccounts extends \Phalcon\Mvc\Model{

	public function initialize(){
		$this->hasOne("blog_id", "Multiple\Backend\Models\Blogs", "blog_id", array(
            'alias' => "blogs"
        ));
	}

	public function createGoogleAccount($g_account, $password){
		$googleAccount->google_account_login = $g_account;
		$googleAccount->google_account_password= $password;
		return $googleAccount->save();
	}
}