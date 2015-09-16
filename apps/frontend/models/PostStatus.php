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

    /**
     * Seta o nome da tabela referenciada pelo model
     */
    public function initialize() {
        $this->setSource("post_status");
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "post_status";
    }
}
