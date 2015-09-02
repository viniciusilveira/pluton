<?php

namespace Multiple\Backend\Models;

class TwitterAccounts extends \Phalcon\Mvc\Model{

	/**
     * Seta o nome da tabela referenciada pelo model
     */
    public function initialize() {
        $this->setSource("twitter_accounts");
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "twitter_accounts";
    }

	public function createTwitterAccount($app_id, $app_secret, $username){
		$twitter_account = new TwitterAccounts();
		$twitter_account->twitter_account_app_id = $app_id;
		$twitter_account->blog_id = 1;
		$twitter_account->twitter_account_app_secret = $app_secret;
		$twitter_account->twitter_account_username = $username;
		$return = $twitter_account->save();
		return $return;
	}

	public function updateTwitterAccount($app_id, $app_secret, $username){
		$twitter_account = TwitterAccounts::FindFirst();
		$twitter_account->twitter_account_app_id = $app_id;
		$twitter_account->twitter_account_app_secret = $app_secret;
		$twitter_account->twitter_account_username = $username;
		$return = $twitter_account->save();

		return $return;
	}
}