<?php
/**
 * Class and Function List:
 * Function list:
 * - getBlog()
 * Classes list:
 * - Blogs extends \
 */
namespace Multiple\Frontend\Models;

class Blogs extends \Phalcon\Mvc\Model {

    private $blog_id;
    private $bolg_name;
    private $blog_layout;

    /**
     * Retorna os dados do blog
     * @return array contendo os dados do blog
     */
    public function getBlog() {
        $blog = Blogs::query()->execute();
        return $blog->getFirst()->blog_id;
    }
}
