<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - getSource()
 * - createUserType()
 * Classes list:
 * - UserType extends \
 */
namespace Multiple\Backend\Models;

class UserType extends \Phalcon\Mvc\Model {

    public $user_type_id;
    public $user_type_abrev;
    public $user_type_descr;
    /**
     * Seta o nome da tabela referenciada pelo model
     */
    public function initialize() {
        $this->setSource("user_type");
        $this->hasMany("user_type_id", "Multiple\Backend\Models\Users", "user_type_id");
    }

    /**
     * @todo: Verificar descrição para este método!
     * @return [type] [description]
     */
    public function getSource() {
        return "user_type";
    }

    /**
     * Insere na tabela user_type um novo tipo de usuário
     * @param  string $user_type_descr Descrição completa do tipo de usuário
     * @param  string $user_type_abrev abreviação do tipo de usuário
     * @return bollean         true caso sucesso, false caso ocorra algum erro
     */
    public function createUserType($user_type_descr, $user_type_abrev) {
        $user_type = new UserType();
        $user_type->user_type_descr = $user_type_descr;
        $user_type->user_type_abrev = $user_type_abrev;
        return $user_type->save();
    }

    public function getAllUserTypes(){
        return UserType::find();

    }
}
