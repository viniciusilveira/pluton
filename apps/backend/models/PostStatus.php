<?php

namespace Multiple\Backend\Models;

class PostStatus extends \Phalcon\Mvc\Model{

	/**
     * Seta o nome da tabela referenciada pelo model
     */
    public function initialize() {
        $this->setSource("post_status");
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "post_status";
    }

    public function getPostStatus($post_status_id = NULL){
    	$post_status = !empty($post_status_id) ? PostStatus::findFirstByPost_status_id($post_status_id) : PostStatus::find();
    	return $post_status;

    }

	public function createPostStatus($post_status_name){
		$post_status = new PostStatus();
		$post_status->post_status_name = $post_status_name;
		return $post_status->save();
	}
}