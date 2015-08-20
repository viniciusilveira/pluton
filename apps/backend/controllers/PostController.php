<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - listPosts()
 * - newPostAction()
 * - insertCategoriesPost()
 * - newCategorieAction()
 * - editPostAction()
 * - deletePostAction()
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

            //busca o usuário logado para exibir como author
            $author = Users::findFirstByUser_id($this->session->get('user_id'));
            $obj_categories = Categories::getCategories();
            foreach ($obj_categories as $categorie) {
                $array_categories[] = $categorie->categorie_name;
            }
            $vars['author'] = $author;
            $vars['categories'] = json_encode($array_categories);
            $vars['post_status'] = PostStatus::getPostStatus();
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
            if($user->user_type_id != 4 && $user->user_type_id != 5){
                $posts = Posts::getPosts();
            } else{
                $posts = Posts::getPosts('users', $user->user_id);
            }

            foreach($posts as $post){
                $post_categories[$post->post_id] = PostCategorie::findByPost_id($post->post_id);
            }
            $this->printArray($post_categories); die();
            $vars['posts'] = $posts;
            $vars['post_categories'] = $post_categories;
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
        $post_content = addslashes(htmlentities($this->request->getPost('post_content')));
        $post_status_id = $this->request->getPost('post_status_id');

        $categories = explode(", ", $this->request->getPost('list_categories'));
        $post_id = Posts::createNewPost($post_date_create, $post_date_posted, $post_date_changed, $post_author, $post_editor, $post_title, $post_content, $post_status_id);
        if ($post_id > 0) $data['success'] = $this->insertCategoriesPost($categories, $post_id);

        echo json_encode($data);
    }

    /**
     * Insere todas as categorias do post na tabela categorie_post
     * @param  array $categories array contendo todas as categorias do post
     * @param  int $id_post    id do post
     * @return boolean             true caso salve todos os ids ou false caso ocorra um erro
     */
    private function insertCategoriesPost($categories, $post_id) {
        foreach ($categories as $categorie) {
            $cat = Categories::findFirstByCategorie_name($categorie);
            $success = PostCategorie::createPostCategorie($post_id, $cat->categorie_id);
            if (!$success) break;
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

    public function editPostAction() {

    }

    public function deletePostAction() {
    }
}
