<?php
/**
* Class and Function List:
* Function list:
* - initialize()
* - getSource()
* - createGoogleAccount()
* - updateGoogleAccount()
* Classes list:
* - GoogleAccounts extends \
*/
namespace Multiple\Backend\Models;
use gapi;

/**
 * Classe responsável por manipular dados da conta google do blog
 */
class GoogleAccounts extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->setSource("google_accounts");
        $this->hasOne("blog_id", "Multiple\Backend\Models\Blogs", "blog_id", array(
            'alias' => "blogs"
        ));
    }

    /**
     * Retorna o nome da tabela do banco de dados a qual a classe se refere
     * @return string Nome da tabela no banco de dados
     */
    public function getSource() {
        return "google_accounts";
    }

    /**
     * Salva os dados referente a API do google
     * @param  string $g_account email da conta informada
     * @param  $key_file_name Nome do arquivo de chave gerado pelo google
     * @param  $g_analytics_script script para verificação de acessos ao site pelo Google Analytics
     * @return boolean            true caso sucesso ou false caso ocorra alguma falha
     */
    public function createGoogleAccount($g_account, $key_file_name, $g_analytics_script, $g_analytics_active, $g_adsense_active) {
        $google_account = new GoogleAccounts();
        $google_account->google_account_login = $g_account;
        $google_account->google_account_key_file_name = $key_file_name;
        $google_account->google_analytics_script = addslashes(htmlentities($g_analytics_script));
        $google_account->google_analytics_active = $g_analytics_active;
        $google_account->google_adsense_active = $g_adsense_active;

        //Valor padrão do id do blog
        $google_account->blog_id = 1;
        $return = $google_account->save();

        return $return;
    }

    /**
     * Atualiza os dados referente a API do google
     * @param  string $g_account email da conta informada
     * @param  $key_file_name Nome do arquivo de chave gerado pelo google
     * @param  $g_analytics_script script para verificação de acessos ao site pelo Google Analytics
     * @return boolean            true caso sucesso ou false caso ocorra alguma falha
     */
    public function updateGoogleAccount($g_account, $key_file_name, $g_analytics_script, $g_analytics_active, $g_adsense_active) {
        $google_account = GoogleAccounts::findFirst();
        $google_account->google_account_login = $g_account;
        $google_account->google_account_key_file_name = $key_file_name;
        $google_account->google_analytics_script = addslashes(htmlentities($g_analytics_script));
        $google_account->google_analytics_active = $g_analytics_active;
        $google_account->google_adsense_active = $g_adsense_active;
        $return = $google_account->save();

        return $return;
    }
}
