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

class BaseController extends \Phalcon\Mvc\Controller {

    public function getUserLoggedInformation() {
        $this->session->start();
        $user = Users::findFirstByUser_id($this->session->get("user_id"));
        $user_name = explode(" ", $user->user_name);
        $vars['user'] = $user_name[0];
        $vars['user_type_id'] = $user->user_type_id;
        $vars['user_img'] = $user->user_img;

        return $vars;
    }

    public function getSideBarMenus() {
        $menus = Menu::find(array(
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
        //echo "<pre>"; print_r($menus); die();
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

    public function mountArrayMonths() {
        $arr_months = array(
            1 => "Janeiro",
            2 => "Fevereiro",
            3 => "Março",
            4 => "Abril",
            5 => "Maio",
            6 => "Junho",
            7 => "Julho",
            8 => "Agosto",
            9 => "Setembro",
            10 => "Outubro",
            11 => "Novembro",
            12 => "Dezembro"
        );
        return $arr_months;
    }
}
