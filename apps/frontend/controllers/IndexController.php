<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - notFoundAction()
 * - nextPageAction()
 * - previusPageAction()
 * - postPageAction()
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

        $page = empty($_REQUEST['page']) ? 0 : $_REQUEST['page'];

        $this->session->start();
        $blog = Blogs::find();
        $posts = $this->getPostsPerPage($page);
        foreach($posts as $post){
            $post_content[$post->post_id] = substr(strip_tags($post->post_content), 0, 1000) . "...";
        }

        $publish_posts = Posts::findByPost_status_id(1);
        $total_publish_posts = count($publish_posts);


        foreach ($posts as $post) {
            $vars['post_title'][$post->post_id] = str_replace(" ", "-", $post->post_title, $count);
        }

        $vars['layout'] = Layouts::findFirst();
        $vars['posts'] = $posts;
        $vars['post_content'] = $post_content;
        $vars['num_pages'] = (($total_publish_posts / 10) > 1) ? ($total_publish_posts / 10) : 1 ;
        $this->view->setVars($vars);

        //caso o blog esteja criado carrega a index; se não carrega a pagina not found
        !empty($blog) ? $this->view->render('index', 'index') : $this->view->pick('index/notFound');
    }

    /**
     * Recebe o número da página e retorna os itens a serem exibidos naquela página
     * @param  int $page número da página a ser exibida na tela
     * @return Resultset      result contendo os posts retornados
     */
    public function getPostsPerPage($page){
        $conditions = "post_status_id = :status:";
        $bind = array(
            "status" => 1
        );
        $order = "post_date_posted DESC, post_id DESC";
        $offset = $page * 10;
        $posts =Posts::find(array(
            "conditions" => $conditions,
            "order" => $order,
            "limit" => 10,
            "offset" => $offset,
            "bind" => $bind,
        ));

        return $posts;
    }

    public function notFoundAction() {

        //view/index/notFound.phtml


    }

    /**
     * Carrega a página de posts exibidindo a postagem informada via REQUEST
     */
    public function postPageAction() {
        $post_id = $this->request->getPost("post_id");

        $post = Posts::findFirstByPost_id($post_id);
        $this->session->start();
        $blog = Blogs::find();
        $vars['layout'] = Layouts::findFirst();
        $vars['post'] = $post;
        $this->view->setVars($vars);
    }
}
