<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - getPostsPerPage()
 * - getCategories()
 * - getPostsIdByCategorie()
 * - getAuthorIdByName()
 * - notFoundAction()
 * - postPageAction()
 * - aboutAction()
 * - contactAction()
 * - setMailLibrary()
 * - sendContactAction()
 * Classes list:
 * - IndexController extends \
 */

namespace Multiple\Frontend\Controllers;

use Multiple\Library\Mail;

use Multiple\Frontend\Models\Users;
use Multiple\Frontend\Models\Blogs;
use Multiple\Frontend\Models\Layouts;
use Multiple\Frontend\Models\Posts;
use Multiple\Frontend\Models\Categories;
use Multiple\Frontend\Models\PostCategorie;
use Multiple\Frontend\Models\GoogleAccounts;

/**
 * Controlador principal do módulo frontend
 */
class IndexController extends \Phalcon\Mvc\Controller {

    /**
     * Carrega a tela principal do blog
     */
    public function indexAction() {
        $this->session->start();
        $page = empty($_REQUEST['page']) ? 0 : $_REQUEST['page'];
        $editor = $this->request->get('editor');
        $user = Users::findFirstByUser_id($this->session->get("user_id"));
        if ($editor && $user->user_type_id == 1) {
            $vars['edit_appearance'] = true;
        }

        $search = $this->request->get('search');
        if (!empty($search)) {
            $str_posts_id = $this->getPostsIdByCategorie($search);
            $str_users_id = $this->getAuthorIdByName($search);
        }
        $g_account = GoogleAccounts::findFirst();
        $vars['script_analytics'] = $g_account->google_analytics_script;

        //var_dump($g_account); die();
        $blog = Blogs::findFirst();

        $posts = $this->getPostsPerPage($page, $search, $str_posts_id, $str_users_id);

        $categories = $this->getCategories($posts);
        foreach ($posts as $post) {
            $post_content[$post->post_id] = substr(strip_tags($post->post_content) , 0, 1000) . "...";
        }

        $publish_posts = Posts::findByPost_status_id(1);
        $total_publish_posts = count($publish_posts);

        foreach ($posts as $post) {
            $vars['post_title'][$post->post_id] = str_replace(" ", "-", $post->post_title, $count);
        }
        $vars['layout'] = Layouts::findFirst();
        $vars['posts'] = $posts;
        $vars['post_content'] = $post_content;
        $vars['categories'] = $categories;
        $vars['num_pages'] = (($total_publish_posts / 10) > 1) ? ($total_publish_posts / 10) : 1;
        $vars['blog_name'] = $blog->blog_name;

        $this->view->setVars($vars);

        //caso o blog esteja criado carrega a index; se não carrega a pagina not found
        !empty($blog) ? $this->view->render('index', 'index') : $this->view->pick('index/notFound');
    }

    /**
     * Recebe o número da página e retorna os itens a serem exibidos naquela página
     * @param  int $page número da página a ser exibida na tela
     * @return \Phalcon\Model\Resultset      result contendo os posts retornados
     */
    public function getPostsPerPage($page, $search = NULL, $str_posts_id = NULL, $str_users_id) {
        $conditions = "post_status_id = :status:";
        $bind = array(
            "status" => 1
        );
        if ($search != NULL) {
            $conditions.= " AND (post_content LIKE :search: OR post_title LIKE :search:)";
            $bind['search'] = "%" . $search . "%";
        }

        if ($str_posts_id != NULL) {
            $conditions.= " OR post_id IN ({$str_posts_id})";
        }

        if ($str_users_id != NULL) {
            $conditions.= "OR post_author IN ({$str_users_id})";
        }

        $order = "post_date_posted DESC, post_id DESC";
        $offset = $page * 10;
        $posts = Posts::find(array(
            "conditions" => $conditions,
            "order" => $order,
            "limit" => 10,
            "offset" => $offset,
            "bind" => $bind,
        ));

        return $posts;
    }

    /**
     * Retorna todas as categorias de um post
     * @param  \Phalcon\Mvc\Resultset $posts Objeto Resultset com informações sobre posts
     * @return array         array contendo todoas as categorias da postagem
     */
    public function getCategories($posts) {
        foreach ($posts as $post) {
            $post_categorie = $post->post_categorie;
            foreach ($post_categorie as $pc) {
                $ct = $pc->categories;
                $count = 0;
                foreach ($ct as $categorie) {
                    $cts[$post->post_id][$count] = $categorie->categorie_name;
                }
            }
        }

        return $cts;
    }

    /**
     * Retrona os ids de postagens com a categoria informada
     * @param  string $categorie_name nome da categoria
     * @return string                 String contendo todos os ids de postagens com a categoria informada
     */
    public function getPostsIdByCategorie($categorie_name) {
        $categories = Categories::find(array(
            "conditions" => "categorie_name LIKE :categorie_name:",
            "bind" => array(
                "categorie_name" => "%" . $categorie_name . "%"
            )
        ));
        foreach ($categories as $categorie) {
            $post_categorie = PostCategorie::findByCategorie_id($categorie->categorie_id);
            foreach ($post_categorie as $pt) {
                $str_post_id = empty($str_post_id) ? $pt->post_id : $str_post_id . ", " . $pt->post_id;
            }
        }
        return $str_post_id;
    }

    /**
     * Retorna o autor pelo nome
     * @param  string $name Nome ou parte do, do autor
     * @return array       Array contendo informações sobre o autor da postagem
     */
    public function getAuthorIdByName($name) {
        $users = Users::find(array(
            "conditions" => "user_login LIKE :user_login:",
            "bind" => array(
                "user_login" => "%" . $name . "%"
            )
        ));

        foreach ($users as $user) {
            $str_user_id = empty($str_user_id) ? $user->user_id : $str_posts_id . ", " . $user->user_id;
        }

        return $str_user_id;
    }

    /**
     * Carrega a view não encontrado
     */
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

        $vars['layout'] = Layouts::findFirst();
        $vars['post'] = $post;
        $this->view->setVars($vars);
    }

    public function aboutAction() {
        $blog = Blogs::findFirst();
        $vars['blog_name'] = $blog->blog_name;
        $vars['blog_about'] = $blog->blog_about;
        $vars['layout'] = Layouts::findFirst();
        $this->view->setVars($vars);
    }

    public function contactAction() {
        $blog = Blogs::findFirst();
        $vars['blog_name'] = $blog->blog_name;
        $vars['layout'] = Layouts::findFirst();
        $this->view->setVars($vars);
    }

    private function setMailLibrary() {
        $blog = Blogs::findFirst();
        return new Mail($blog->blog_mail, $blog->blog_mail_password);
    }

    public function sendContactAction() {
        $this->view->disable();

        $name = $this->request->getPost("name");
        $email = $this->request->getPost("email");
        $message = $this->request->getPost("message");
        $mail = IndexController::setMailLibrary();
        $data['success'] = $mail->sendContactMessage("Contato", $email, $message);

        echo json_encode($data);
    }
}
