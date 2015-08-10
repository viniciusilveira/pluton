<?php
namespace Multiple\Backend\Controllers;

use Phalcon\Session\Adapter\Files as Session;
class BaseController extends \Phalcon\Mvc\Controller
{

    /**
     * Função para imprimir array na tela (somente para debug)
     * @param  array $array 
     * @return Sem retorno
     */
    public function printArray($array){
    	echo "<pre>";
    	print_r($array);
    	echo "</pre>";
    }

    

    /**
     * Gera uma string aleatória contendo números letras maiusculas e minusculas
     * @param type $l tamanho da chave a ser gerada (6 padrão)
     * @return string chave eletronica gerada
     */
    function uid($l = 6) {
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        
        $uid = null;
        $loop = false;
        do {
            while (strlen($uid) < $l){
                $uid .= $s[mt_rand(0, (strlen($s) - 1))];
            }
            $params = array(
              'uid' => $uid
            );
            
            $loop = (empty($result)) ? false : true;
        } while ($loop);
        
        return $uid;
    }

}
