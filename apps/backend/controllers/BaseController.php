<?php
namespace Multiple\Backend\Controllers;

use Phalcon\Session\Adapter\Files as Session;
class BaseController extends SetupController
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
}
