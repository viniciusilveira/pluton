<?php
/**
* Class and Function List:
* Function list:
* - initialize()
* - getSource()
* - createFacebookPage()
* - updateFacebookPage()
* Classes list:
* - FacebookPages extends \
*/
namespace Multiple\Backend\Models;

/**
 * Classe responsável por manipular dados referentes ao Facebook
 */
class FacebookPages extends \Phalcon\Mvc\Model {

    /**
     * Seta o nome da tabela referenciada pelo model
     */
    public function initialize() {
        $this->setSource("facebook_pages");
    }

    /**
     * Retorna o nome da tabela a qual a classe idententifica no banco de dados
     * @return string Nome da classe
     */
    public function getSource() {
        return "facebook_pages";
    }

    /**
     * Insere dados referente a página do facebook
     * @param  string $page_name nome da página
     * @return boolean            verdadeiro caso sucesso ou falso caso ocorra alguma falha
     */
    public function createFacebookPage($page_name) {
        $facebook_page = new FacebookPages();
        $facebook_page->blog_id = 1;
        $facebook_page->facebook_page_name = $page_name;
        $return = $facebook_page->save();

        return $return;
    }

    /**
     * Atualiza dados referente a página do facebook
     * @param  string $page_name nome da página
     * @return boolean            verdadeiro caso sucesso ou falso caso ocorra alguma falha
     */
    public function updateFacebookPage($page_name) {
        $facebook_page = FacebookPages::findFirst();
        $facebook_page->facebook_page_name = $page_name;
        $return = $facebook_page->save();

        return $return;
    }
}
