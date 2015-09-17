<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - getSource()
 * Classes list:
 * - PostStatus extends \
 */
namespace Multiple\Backend\Models;

class PostStatus extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->setSource("post_status");
    }

    /**
     * Retorna o nome da tabela ao qual a classe referencia no banco de dados
     * @return string nome da tabela
     */
    public function getSource() {
        return "post_status";
    }
}
