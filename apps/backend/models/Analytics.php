<?php

namespace Multiple\Backend\Models;

class Analytics extends \Phalcon\Mvc\Model{

	public function initialize(){
		$this->hasOne("blog_id", "Multiple\Backend\Models\Blogs", "blog_id", array(
            'alias' => "blogs"
        ));
	}
}