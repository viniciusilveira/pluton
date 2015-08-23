<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - listPostsAction()
 * - newPostAction()
 * - editPostAction()
 * - insertCategoriesPost()
 * - updateCategoriesPost()
 * - newCategorieAction()
 * - getCategoriesByPost()
 * Classes list:
 * - PostController extends BaseController
 */

namespace Multiple\Backend\Controllers;
use Multiple\Backend\Models\Posts;
use Multiple\Backend\Models\PostStatus;
use Multiple\Backend\Models\Categories;
use Multiple\Backend\Models\Users;
use Multiple\Backend\Models\PostCategorie;

/**
 * Classe responsável pela manipulação das postagens e elementos relacionados as mesmas
 */
class PostController extends BaseController {

    /**
     * Carrega a view para criação de um novo post
     */
    public function indexAction() {
        $this->session->start();
        if ($this->session->get('user_id') != NULL) {
            $edit_post = false;
            if ($this->request->getPost('post_id') != NULL) {

                $post[0] = Posts::findFirstByPost_id($this->request->getPost('post_id'));
                foreach ($post as $p) {
                    $post_content = $string = str_replace(PHP_EOL, '', html_entity_decode($p->post_content));
                    $post_date = $this->dateFormat($p->post_date_posted, 2);
                }
                $edit_post = true;
                $vars['post_categories'] = $this->getCategoriesByPost($post);
                $vars['post_content'] = $post_content;
                $vars['post_date'] = $post_date;
                $vars['post'] = $post[0];
            }

            //busca o usuário logado para exibir como author
            $author = Users::findFirstByUser_id($this->session->get('user_id'));
            $obj_categories = Categories::getCategories();
            foreach ($obj_categories as $categorie) {
                $array_categories[] = $categorie->categorie_name;
            }
            $vars['author'] = $author;
            $vars['categories'] = json_encode($array_categories);
            $vars['post_status'] = PostStatus::getPostStatus();
            $vars['edit_post'] = $edit_post;
            $this->view->setVars($vars);
            $this->view->render("post", "index");
        }
        else {
            $this->response->redirect(URL_PROJECT . 'settings');
        }
    }

    /**
     * Carrega a view lasPosts filtrando os dados conforme solicitado
     * @param  string $filter tipo de filtro
     * @param  [type] $value  valor a ser filtrado; tipo pode varia
     */
    public function listPostsAction() {
        $this->session->start();

        if ($this->session->get('user_id') != NULL) {
            $user = Users::findFirstByUser_id($this->session->get('user_id'));
            if ($user->user_type_id != 4 && $user->user_type_id != 5) {
                $posts = Posts::find(array(
                    "order" => "post_date_posted DESC"
                ));
            }
            else {
                $posts = Posts::findByUser_id($user->user_id);
            }

            $vars['posts'] = $posts;
            $vars['categories'] = $this->getCategoriesByPost($posts);

            $this->view->setVars($vars);
            $this->view->render("post", "listPosts");
        }
        else {
            $this->response->redirect(URL_PROJECT . 'settings');
        }
    }

    /**
     * Cria uma nova postagem utilizando os dados recebidos via Post
     * @return boolean true caso a postagem tenha sido salva ou false caso contrário
     */
    public function newPostAction() {
        $this->view->disable();

        $post_date_create = date("Y-m-d H:i:s");
        $post_date_posted = $this->dateFormat($this->request->getPost('post_date_posted') , 1);
        $post_date_changed = date("Y-m-d H:i:s");
        $post_author = $this->request->getPost('post_author');
        $post_editor = $this->request->getPost('post_author');
        $post_title = $this->request->getPost('post_title');
        $post_content = htmlentities($this->request->getPost('post_content'));
        //print_r($post_content); die();
        $post_status_id = $this->request->getPost('post_status_id');

        $categories = explode(", ", $this->request->getPost('list_categories'));
        $post_id = Posts::createNewPost($post_date_create, $post_date_posted, $post_date_changed, $post_author, $post_editor, $post_title, $post_content, $post_status_id);
        if ($post_id > 0) $data['success'] = $this->insertPostCategories($categories, $post_id);

        echo json_encode($data);
    }

    /**
     * Recebe os dados via POST e salva as alterações recebidas no banco de dados
     * @return [type] [description]
     */
    public function editPostAction() {
        $this->view->disable();

        $post_id = $this->request->getPost("post_id");
        $post_date_posted = $this->dateFormat($this->request->getPost('post_date_posted') , 1);
        $post_date_changed = date("Y-m-d H:i:s");
        $post_author = $this->request->getPost('post_author');
        $post_editor = $this->request->getPost('post_author');
        $post_title = $this->request->getPost('post_title');
        $post_content = addslashes(htmlentities($this->request->getPost('post_content')));
        $post_status_id = $this->request->getPost('post_status_id');
        $categories = explode(", ", $this->request->getPost('list_categories'));
        $post_id = Posts::updatePostAction($post_id, $post_date_posted, $post_date_changed, $post_author, $post_editor, $post_title, $post_content, $post_status_id);
        if ($post_id > 0) $data['success'] = $this->updatePostCategories($categories, $post_id);

        echo json_encode($data);
    }

    /**
     * Insere todas as categorias do post na tabela categorie_post
     * @param  array $categories array contendo todas as categorias do post
     * @param  int $id_post    id do post
     * @return boolean             true caso salve todos os ids ou false caso ocorra um erro
     */
    private function insertPostCategories($categories, $post_id) {
        foreach ($categories as $categorie) {
            $cat = Categories::findFirstByCategorie_name($categorie);
            $success = PostCategorie::createPostCategorie($post_id, $cat->categorie_id);
            if (!$success) break;
        }
        return $success;
    }

    /**
     * Recebe as novas categorias e o id da postagem; Remove todos os PostCategories antigos e insere os novos;
     * @param  array $categories array contendo todas as categorias do post
     * @param  int $post_id  id do post
     * @return boolean             true caso salve todos os ids ou false caso ocorra um erro
     */
    public function updatePostCategories($categories, $post_id) {
        foreach($categories as $categorie){
            $cat = Categories::findFirstByCategorie_name($categorie);
            $success = PostCategorie::deleteAllPostCategorieByPost($post_id);
            $success = $success ? PostCategorie::createPostCategorie($post_id, $cat->categorie_id) : $success;
            if(!$success) break;
        }
        return $success;
    }

    /**
     * Verifica se uma categoria informada já existe, caso não, insere no banco de dados
     * @return json array contendo um boolean para informar o sucesso ou falha da operação
     */
    public function newCategorieAction() {
        $this->view->disable();
        $new_categorie = $this->request->getPost("categorie");
        $categories = Categories::find();
        $exists = 1;
        foreach ($categories as $categorie) {
            if ($exists != 0) {
                $exists = strcmp($categorie->categorie_name, $new_categorie);
            }
            else {
                break;
            }
        }
        $data['success'] = ($exists != 0) ? Categories::newCategorie($new_categorie) : true;
        echo json_encode($data);
    }

    /**
     * Recebe um objeto do tipo ResultSet[Posts] e retorna todas as categorias dos posts do objeto
     * @param  object $posts ResultSet[Posts]
     * @return array        Array contendo o array de cada post e uma string com as categorias dos posts
     */
    public function getCategoriesByPost($posts) {
        foreach ($posts as $post) {
            $post_categories = $post->post_categorie;
            foreach ($post_categories as $post_categorie) {
                $categories = $post_categorie->categories;
                foreach ($categories as $categorie) {
                    $ctg[$post->post_id].= empty($ctg[$post->post_id]) ? $categorie->categorie_name : ", " . $categorie->categorie_name;
                }
            }
        }
        return $ctg;
    }
}
