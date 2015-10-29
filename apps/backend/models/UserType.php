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

/**
 * Classe responsável por manipular dados referentes aos tipos de usuários
 */
class UserType extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->setSource("user_type");
    }

    /**
     * Retorna o nome da tabela ao qual a classe referencia no banco de dados
     * @return string nome da tabela
     */
    public function getSource() {
        return "user_type";
    }

    /**
     * Cria um novo tipo de Usuário
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
}
