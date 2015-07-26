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

   /**
    * Insere os dados do layout no banco de dados
    * @return boolean
    */
    public function createLayout() {
        $layout = new Layouts();
        //Caso não seja passado os dados do layout, cria um padrão
        $layout->layout_background_color = "white";
        $layout->layout_font_color = "black";
        $layout->layout_active = true;
        $layout->layout_menu1 = "Home";
        $layout->layout_menu2 = "Menu 2";
        $layout->layout_menu3 = "Menu 3";
        $layout->layout_menu4 = "Menu 4";
        $layout->layout_menu5 = "Menu 5";
        $success = $layout->save();

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
