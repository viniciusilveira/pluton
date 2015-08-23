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
use Multiple\Frontend\Models\Posts;

class IndexController extends \Phalcon\Mvc\Controller {

    /**
     * Verifica se o sistema foi instalado e o blog criado, caso sim carrega a página inicial do blog,
     * se não carrega a página de não encontrado
     * @return [type] [description]
     */
    public function indexAction() {
        $this->session->start();
        $blog = Blogs::find();
        $vars['layout'] = Layouts::findFirst();
        $vars['posts'] = Posts::findByPost_status_id(1);
        foreach($vars['posts'] as $post){
            //echo $post->post_content . "<br/><br/><br/>";
        } //die();
        $this->view->setVars($vars);

        //caso o blog esteja criado carrega a index; se não carrega a pagina not found
        !empty($blog) ? $this->view->render('index', 'index') : $this->view->pick('index/notFound');
    }

    public function notFoundAction() {

        //view/index/notFound.phtml

    }

    /**
     * Carrega a página de posts exibidindo a postagem informada via REQUEST
     */
    public function postPageAction(){
        $post_id = $this->request->getPost("post_id");

        $post = Posts::findFirstByPost_id($post_id);
        $this->session->start();
        $blog = Blogs::find();
        $vars['layout'] = Layouts::findFirst();
        $vars['post'] = $post;
        $this->view->setVars($vars);

    }
}
