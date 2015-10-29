<?php
/**
 * Class and Function List:
 * Function list:
 * - printArray()
 * - getUserLoggedInformation()
 * - getSideBarMenus()
 * - uid()
 * - dateFormat()
 * - mountArrayMonths()
 * Classes list:
 * - BaseController extends \
 */
namespace Multiple\Backend\Controllers;

use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Mvc\Model\Resultset;

use Multiple\Backend\Models\Users;
use Multiple\Backend\Models\Menu;
use Multiple\Backend\Models\Submenu;


/**
 * Controlador principal do sistema, herdado por todos os outros controlladores
 */
class BaseController extends \Phalcon\Mvc\Controller {

    /**
     * Busca todas as informações do usuário logado
     * @return array contendo nome de usuário, tipo e imagem de perfil do usuário logado
     */
    public function getUserLoggedInformation() {
        $this->session->start();
        $user = Users::findFirstByUser_id($this->session->get("user_id"));
        $user_name = explode(" ", $user->user_name);
        $vars['user'] = $user_name[0];
        $vars['user_type_id'] = $user->user_type_id;
        $vars['user_img'] = $user->user_img;

        return $vars;
    }

    /**
     * Busca as opções a serem exibidas no menu lateral do sistema
     * @return array contendo menus e submenus
     */
    public function getSideBarMenus() {
        $menus = Menu::find(array(
            'conditions' => 'menu_active = 1',
            'hydration' => Resultset::HYDRATE_ARRAYS
        ))->toArray();

        foreach ($menus as $id => $menu) {
            $menus[$id]['submenu'] = Submenu::find(array(
                "conditions" => "menu_id = :menu_id:",
                "bind" => array(
                    "menu_id" => $menu["menu_id"]
                ) ,
                "hydration" => Resultset::HYDRATE_ARRAYS
            ))->toArray();
            if(!empty($menus[$id]['submenu'])){
                $menus[$id]['submenu_div_name'] = str_replace("#", "",$menus[$id]['menu_href']);
            }
        }

        return $menus;
    }

    /**
     * Gera uma string aleatória contendo números letras maiusculas e minusculas
     * @param type $l tamanho da chave a ser gerada (6 padrão)
     * @return string chave eletronica gerada
     */
    public function uid($l = 6) {
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $uid = null;
        $loop = false;
        do {
            while (strlen($uid) < $l) {
                $uid.= $s[mt_rand(0, (strlen($s) - 1)) ];
            }
            $params = array(
                'uid' => $uid
            );

            $loop = (empty($result)) ? false : true;
        } while ($loop);

        return $uid;
    }

    /**
     * Altera o formato da data para human ou database conforme o parametro informado
     * @param  date $data   data a ser alterada
     * @param  int $format indica o formato da data. 1 => Human, 2 => database
     * @return date         data formatada
     */
    public function dateFormat($data, $format) {
        if ($format == 1) {
            return join('-', array_reverse(explode('/', $data)));
        }
        else {
            return join('/', array_reverse(explode('-', $data)));
        }
    }

    /**
     * Monta um array com o nome dos 12 meses do calendário em pt_BR
     * @return array contendo os meses do calendário em pt_BR
     */
    public function mountArrayMonths() {
        $arr_months = array(
            0 => "Janeiro",
            1 => "Fevereiro",
            2 => "Março",
            3 => "Abril",
            4 => "Maio",
            5 => "Junho",
            6 => "Julho",
            7 => "Agosto",
            8 => "Setembro",
            9 => "Outubro",
            10 => "Novembro",
            11 => "Dezembro"
        );
        return $arr_months;
    }
}
