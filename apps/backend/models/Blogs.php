<?php
namespace Multiple\Backend\Models;

/**
 * Class Blogs
 * @package Multiple\Backend\Models
 */
class Blogs extends \Phalcon\Mvc\Model
{
	public $blog_id;
	public $bolg_name;
	public $blog_layout;
	
	/**
	 * Verifica se já existe um blog criado no sistema
	 * @return boolean true caso exista, false caso não exista
	 */
	public function verifyBlogExistAction() {
		return $this->count() > 0 ? true : false;
	}
}
