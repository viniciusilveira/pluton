<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - getSource()
 * - createTwitterAccount()
 * - updateTwitterAccount()
 * Classes list:
 * - TwitterAccounts extends \
 */
namespace Multiple\Backend\Models;


/**
 * Classe responsável pelo gerenciamento de dados referente a contas da rede social twitter
 */
class TwitterAccounts extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->setSource("twitter_accounts");
    }

    /**
     * Retorna o nome da tabela ao qual a classe referencia no banco de dados
     * @return string nome da tabela
     */
    public function getSource() {
        return "twitter_accounts";
    }

    /**
     * Insere dados referentes ao twitter
     * @param  string $app_id     APP ID fornecido pelo twitter
     * @param  string $app_secret APP SECRET fornecido pelo twitter
     * @param  string $username   Nome do usuário a ser monitorado
     * @return boolean  Verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function createTwitterAccount($app_id, $app_secret, $username, $tw_active) {
        $twitter_account = new TwitterAccounts();
        $twitter_account->twitter_account_app_id = $app_id;
        $twitter_account->blog_id = 1;
        $twitter_account->twitter_account_app_secret = $app_secret;
        $twitter_account->twitter_account_username = $username;
        $twitter_account->twitter_active = $tw_active;
        $return = $twitter_account->save();
        return $return;
    }

    /**
     * Atualiza os dados referentes ao twitter
     * @param  string $app_id     APP ID fornecido pelo twitter
     * @param  string $app_secret APP SECRET fornecido pelo twitter
     * @param  string $username   Nome do usuário a ser monitorado
     * @return boolean  Verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function updateTwitterAccount($app_id, $app_secret, $username, $tw_active) {
        $twitter_account = TwitterAccounts::FindFirst();
        $twitter_account->twitter_account_app_id = $app_id;
        $twitter_account->twitter_account_app_secret = $app_secret;
        $twitter_account->twitter_account_username = $username;
        $twitter_account->twitter_active = $tw_active;
        $return = $twitter_account->save();

        return $return;
    }
}
