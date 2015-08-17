<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - notFoundAction()
 * Classes list:
 * - IndexController extends \
 */

namespace Multiple\Frontend\Controllers;
use Multiple\Frontend\Models\Blogs;
use Multiple\Frontend\Models\Layouts;

class IndexController extends \Phalcon\Mvc\Controller {

    /**
     * Verifica se o sistema foi instalado e o blog criado, caso sim carrega a página inicial do blog,
     * se não carrega a página de não encontrado
     * @return [type] [description]
     */
    public function indexAction() {
        $this->session->start();

        $blog = Blogs::findFirst();
        $layout = Layouts::findFirst();
        //var_dump($layout); die();
        $vars['layout'] = $layout;
        $this->view->setVars($vars);

        //caso o blog esteja criado carrega a index; se não carrega a pagina not found
        !empty($blog) ? $this->view->render('index', 'index') : $this->view->pick('index/notFound');
    }

    public function notFoundAction() {

        //view/index/notFound.phtml

    }
}
