<?php
/**
* Class and Function List:
* Function list:
* - initialize()
* Classes list:
* - GoogleAccounts extends \
*/
namespace Multiple\Frontend\Models;

class GoogleAccounts extends \Phalcon\Mvc\Model {
    public function initialize() {
        $this->setSource("google_accounts");
        $this->hasOne("blog_id", "Multiple\Backend\Models\Blogs", "blog_id", array(
            'alias' => "blogs"
        ));
    }
}
