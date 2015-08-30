<?php

namespace Multiple\Backend\Models;

class FacebookAccounts extends \Phalcon\Mvc\Model{
	/**
     * Seta o nome da tabela referenciada pelo model
     */
    public function initialize() {
        $this->setSource("facebook_accounts");
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "facebook_accounts";
    }

    public function createFacebookAccount($facebook_account_app_id, $facebook_account_app_secret){

        $facebook_account = new FacebookAccounts;
        $facebook_account->blog_id = 1;
        $facebook_account->facebook_account_app_id = $facebook_account_app_id;
        $facebook_account->facebook_account_app_secret = $facebook_account_app_secret;
        $return = $facebook_account->save();

        return $return;
    }

    public function updateFacebookAccount($facebook_account_app_id, $facebook_account_app_secret){
        $facebook_account = FacebookAccounts::findFirst();

        $facebook_account->facebook_account_app_id = $facebook_account_app_id;
        $facebook_account->facebook_account_app_secret = $facebook_account_app_secret;
        $return = $facebook_account->save();

        return $return;
    }
}