<?php
/**
 * Class and Function List:
 * Function list:
 * - createLayout()
 * - getLayout()
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
    * Insere os dados do layout no banco de dados
    * @return boolean
    */
    public function createLayout() {

        //Caso não seja passado os dados do layout, cria um padrão
        $this->layout_background_color = "white";
        $this->layout_font_color = "black";
        $this->layout_active = true;
        $this->layout_menu1 = "Home";
        $this->layout_menu2 = "Menu 2";
        $this->layout_menu3 = "Menu 3";
        $this->layout_menu4 = "Menu 4";
        $this->layout_menu5 = "Menu 5";
        $success = $this->create();

        return $success;
    }

    /**
     * Busca um layout pelo id
     * @param  int $id_layout id do layout
     * @return array            array com os dados do layout retornado
     */
    public function getLayout($id_layout) {
    }

    /**
     * Remove um layout do sistema
     * @return [type] [description]
     */
    public function removeLayout($layout_id) {
    }
}
