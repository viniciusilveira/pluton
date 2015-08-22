<?php
/**
 * Class and Function List:
 * Function list:
 * - onConstruct()
 * - createNewPost()
 * - getPosts()
 * - editPost()
 * - publishPost()
 * - unpublishPost()
 * Classes list:
 * - Posts extends \
 */
namespace Multiple\Backend\Models;

class Posts extends \Phalcon\Mvc\Model {

    public function onConstruct() {
        $this->hasOne("post_author", "Multiple\Backend\Models\Users", "user_id", array(
            'alias' => 'author'
        ));
        $this->hasOne("post_editor", "Multiple\Backend\Models\Users", "user_id", array(
            'alias' => 'editor'
        ));
        $this->hasOne("post_status_id", "Multiple\Backend\Models\PostStatus", "post_status_id", array(
            'alias' => 'post_status'
        ));

        $this->HasMany("post_id", "Multiple\Backend\Models\PostCategorie", "post_id", array(
            'alias' => 'post_categorie'
        ));
    }

    /**
     * Cria uma nova postagem no banco de dados
     * @param  date $post_date_create  data da criação do post
     * @param  date $post_date_posted  data que aparecerá no blog como postado
     * @param  date $post_date_changed data da ultima modificação da postagem
     * @param  int $post_author       id do autor da postagem
     * @param  int $post_editor       id do editor da postagem
     * @param  string $post_title        titulo do post
     * @param  string $post_content      conteudo da postagem
     * @param  id $post_status_id    status do post
     * @return int id da nova postagem caso sucesso ou zero caso false
     */
    public function createNewPost($post_date_create, $post_date_posted, $post_date_changed, $post_author, $post_editor, $post_title, $post_content, $post_status_id) {
        $post = new Posts;
        $post->post_blog = 1;
        $post->post_date_create = $post_date_create;
        $post->post_date_posted = $post_date_posted;
        $post->post_date_changed = $post_date_changed;
        $post->post_author = $post_author;
        $post->post_editor = $post_editor;
        $post->post_title = $post_title;
        $post->post_content = $post_content;
        $post->post_status_id = $post_status_id;
        if ($post->save()) {
            return $post->post_id;
        }
        else {
            return -1;
        }
    }

    public function updatePostAction($post_id, $post_date_posted, $post_date_changed, $post_author, $post_editor, $post_title, $post_content, $post_status_id) {

        $post = Posts::findFirstByPost_id($post_id);
        $post->post_date_posted = $post_date_posted;
        $post->post_date_changed = $post_date_changed;
        $post->post_author = $post_author;
        $post->post_editor = $post_editor;
        $post->post_title = $post_title;
        $post->post_content = $post_content;
        $post->post_status_id = $post_status_id;
        if ($post->save()) {
            return $post->post_id;
        }
        else {
            return -1;
        }
    }

    /**
     * Busca usuários pelo filtro informado
     * @param  string $filter tipo do filtro; Pode ser users, categories, date e status
     * @param  [type] $value  valor para o filtro; o Tipo pode variar dependendo do filtro para busca
     * @return [type]         [description]
     */
    public function getPosts($filter = NULL, $value = NULL, $initial_date = NULL) {

        //Verifica qual o filtro da consulta e carrega as opções necessárias
        switch ($filter) {
            case 'users':
                $conditions = "post_author = :user:";
                $bind = array(
                    "user" => intval($value)
                );
                $order = "post_date_posted DESC";
            break;
            case 'categories':
                $conditions = "post_categorie_id = :categorie:";
                $bind = array(
                    "categorie" => intval($value)
                );
                $order = "post_date_posted DESC";
            break;
            case 'date':
                if ($value != NULL) {
                    $conditions = "post_date_posted >= :date:";
                    $bind = array(
                        "date" => $value
                    );
                    $order = "post_date_posted DESC";
                }
                else {
                    $order = "post_date_posted DESC";
                }
            break;
            case 'status':
                $conditions = "post_status_id = :status:";
                $bind = array(
                    "status" => intval($value)
                );
                $order = "post_date_posted DESC";
            break;
            default:
                $conditions = NULL;
                $bind = NULL;
                $order = NULL;
            break;
        }

        if ($initial_date != NULL) {
            $conditions = empty($conditions) ? " post_date_posted >= :initial_date:" : " AND post_date_posted >= :initial_date:";
        }

        $posts = Posts::find(array(
            "conditions" => $conditions,
            "order" => $order,
            "limit" => 15,
            "bind" => $bind,
        ));

        return $posts;
    }
}
