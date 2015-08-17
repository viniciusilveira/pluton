<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - newPostAction()
 * - newCategorieAction()
 * - editPostAction()
 * - deletePostAction()
 * - getPosts()
 * - publishPostAction()
 * - unpublishPost()
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

        //busca o usuário logado para exibir como author
        $authors = Users::findByUser_id($this->session->get('user_id'));
        $obj_categories = Categories::getCategories();
        foreach ($obj_categories as $categorie) {
            $array_categories[] = $categorie->categorie_name;
        }
        $vars['authors'] = $authors;
        $vars['categories'] = json_encode($array_categories);
        $vars['post_status'] = PostStatus::getPostStatus();
        $this->view->setVars($vars);
        $this->view->render("post", "index");
    }

    /**
     * Cria uma nova postagem utilizando os dados recebidos via Post
     * @return boolean true caso a postagem tenha sido salva ou false caso contrário
     */
    public function newPostAction() {
        $title = $this->request->getPost('post_title');
        $content = $this->request->getPost('post_content');
        $author = $this->request->getPost('post_author');
        $editor = $this->request->getPost('post_author');
        $status = $this->request->getPost('post_status_id');
        $date_create = date('d/m/y');
        $date_changed = date('d/m/y');
        $date_posted = $this->request->getPost('post_date_posted');
        $categories = implode(", ", $this->request->getPost('list_categories'));
        //@todo: Continuar daqui
        $success = Posts::createNewPost($title, $content, $author, $editor, $status, $date_create, $date_posted, $categories);
        if($success) $this->insertCategoriesPost($categories, Posts::lastInsertId());

        return $success;
    }

    /**
     * Insere todas as categorias do post na tabela categorie_post
     * @param  array $categories array contendo todas as categorias do post
     * @param  int $id_post    id do post
     * @return boolean             true caso salve todos os ids ou false caso ocorra um erro
     */
    public function insertCategoriesPost($categories, $id_post){
        $post_categorie = new PostCategorie();
        foreach($categories as $categorie){
            $cat = Categories::findFirstByCategorie_name($categorie);
        }
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

    public function getPosts() {
    }

    public function publishPostAction() {
    }

    public function unpublishPost() {
    }
}
