<?php

namespace Multiple\Backend\Models;

class FacebookPages extends \Phalcon\Mvc\Model{
	/**
     * Seta o nome da tabela referenciada pelo model
     */
    public function initialize() {
        $this->setSource("facebook_pages");
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "facebook_pages";
    }

    public function createFacebookPage($page_name){
        $facebook_page = new FacebookPages();
        $facebook_page->blog_id = 1;
        $facebook_page->facebook_page_name= $page_name;
        $return = $facebook_page->save();

        return $return;

    }

    public function updateFacebookPage($page_name){
        $facebook_page = FacebookPages::findFirst();
        $facebook_page->facebook_page_name = $page_name;
        $return = $facebook_page->save();

        return $return;
    }
}