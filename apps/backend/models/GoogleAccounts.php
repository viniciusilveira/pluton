<?php
namespace Multiple\Backend\Models;
use gapi;

/**
 * Classe responsável por manipular dados da conta google do blog
 */
class GoogleAccounts extends \Phalcon\Mvc\Model
{

    public function initialize() {
        $this->setSource("google_accounts");
        $this->hasOne("blog_id", "Multiple\Backend\Models\Blogs", "blog_id", array('alias' => "blogs"));
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "google_accounts";
    }

    /**
     * Salva os dados da conta informado pelo usuário no banco de dados
     * @param  string $g_account email da conta informada
     * @param  string $password  senha da conta informada
     * @return boolean            true caso sucesso ou false caso ocorra alguma falha
     */
    public function createGoogleAccount($g_account, $key_file_name) {
        $googleAccount = new GoogleAccounts();
        $googleAccount->google_account_login = $g_account;
        $googleAccount->google_account_key_file_name = $key_file_name;

        //Valor padrão do id do blog
        $googleAccount->blog_id = 1;
        $return = $googleAccount->save();

        return $return;
    }

    public function updateGoogleAccount($g_account, $key_file_name){
        $google_account = GoogleAccounts::findFirst();
        $google_account->google_account_login = $g_account;
        $google_account->google_account_key_file_name = $key_file_name;
        $return = $google_account->save();

        return $return;
    }
}
