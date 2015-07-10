<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - maintenceAction()
 * Classes list:
 * - IndexController extends \
 */

namespace Multiple\Frontend\Controllers;
use Multiple\Frontend\Models\Blogs;

class IndexController extends \Phalcon\Mvc\Controller {

    /**
     * Verifica se o sistema foi instalado e o blog criado, caso sim carrega a página inicial do blog,
     * se não carrega a página de não encontrado
     * @return [type] [description]
     */
    public function indexAction() {
        $blogs = new Blogs();
        $blog = $blogs->getBlog();
        //caso o blog esteja criado carrega a index; se não carrega a pagina not found
        !empty($blog) ? $this->view->render('index', 'index') : $this->view->pick('index/notFound');
    }

    public function notFoundAction() {
        //view/index/notFound.phtml
    }
}
