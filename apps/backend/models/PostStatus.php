<?php
/**
* Class and Function List:
* Function list:
* - initialize()
* - getSource()
* - getPostStatus()
* - createPostStatus()
* Classes list:
* - PostStatus extends \
*/
namespace Multiple\Backend\Models;

class PostStatus extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->setSource("post_status");
    }

    /**
     * Seta o nome da tabela ao qual a classe referencia no banco de dados
     * @return string nome da tabela
     */
    public function getSource() {
        return "post_status";
    }

    /**
     * Retorna status da postagem por id
     * @param  int $post_status_id id de um status de postagem
     * @return \Phalcon\Mvc\Model\Resultset  Objeto contendo o status de postagens
     */
    public function getPostStatus($post_status_id = NULL) {
        $post_status = !empty($post_status_id) ? PostStatus::findFirstByPost_status_id($post_status_id) : PostStatus::find();
        return $post_status;
    }

    /**
     * Cria um novo status de postagem
     * @param  string $post_status_name Nome do status
     * @return boolean                   Verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function createPostStatus($post_status_name) {
        $post_status = new PostStatus();
        $post_status->post_status_name = $post_status_name;
        return $post_status->save();
    }
}
