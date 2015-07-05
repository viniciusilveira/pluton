<?php
/**
 * Class and Function List:
 * Function list:
 * - addLayout()
 * - removeLayout()
 * Classes list:
 * - Layouts extends \
 */
namespace Multiple\Backend\Models;

/**
 * Class Layouts
 * @package Multiple\Backend\Models
 */
class Layouts extends \Phalcon\Mvc\Model {
    private $layout_id;
    private $layout_view;
    private $layout_banner;
    private $layout_background_color;
    private $layout_font_color;
    private $layout_active;
    private $layout_menu1;
    private $layout_menu2;
    private $layout_menu3;
    private $layout_menu4;
    private $layout_menu5;
    private $layout_footer;

    /**
     * Adiciona um novo layout no sistema
     * @param array $array_layout Array contendo os dados necessários para criar o layout
     */
    public function addLayout($array_layout = NULL) {
        //Caso não seja passado os dados do layout, cria um padrão
        if (empty($array_layout)) {
            $this->layout_background_color = "white";
            $this->layout_font_color = "black";
            $this->layout_active = true;
            $this->layout_menu1 = "Home";
            $this->layout_menu2 = "Menu 2";
            $this->layout_menu3 = "Menu 3";
            $this->layout_menu4 = "Menu 4";
            $this->layout_menu5 = "Menu 5";
        }
        //Se os dados forem informados, cria um layout com os dados informados pelo usuário
        else {
        }
    }

    /**
     * Busca um layout pelo id
     * @param  int $id_layout id do layout
     * @return array            array com os dados do layout retornado
     */
    public function getLayout($id_layout){

    }

    /**
     * Remove um layout do sistema
     * @return [type] [description]
     */
    public function removeLayout($layout_id) {
    }

}
