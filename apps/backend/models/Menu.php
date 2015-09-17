<?php
/**
 * Class and Function List:
 * Function list:
 * - createMenu()
 * - updateMenu()
 * Classes list:
 * - Menu extends \
 */
namespace Multiple\Backend\Models;

/**
 * Classe responsável por manipular dados referentes ao menu
 */
class Menu extends \Phalcon\Mvc\Model {

    /**
     * Cria um novo menu
     * @param  string $menu_icon             nome do novo icone, pode ser referente a glyphicons do bootstrap(http://getbootstrap.com/components/)
     * ou os icônes do font awesome (http://fortawesome.github.io/Font-Awesome/3.2.1/icons/)
     * @param  string $menu_name             nome do menu
     * @param  string $menu_href             Endereço que deve acessar
     * @param  int $menu_level_permission    Nível de permissão do menu
     * @return int                        id do menu inserido
     */
    public function createMenu($menu_icon, $menu_name, $menu_href, $menu_level_permission) {
        $menu = new Menu();
        $menu->menu_icon = $menu_icon;
        $menu->menu_name = $menu_name;
        $menu->menu_href = $menu_href;
        $menu->menu_level_permission = $menu_level_permission;
        $menu->save();

        return $menu->menu_id;
    }

    /**
     * Atualiza um menu existente
     * @param int menu_id id do menu ao ser atualizado
     * @param  string $menu_icon             nome do novo icone, pode ser referente a glyphicons do bootstrap(http://getbootstrap.com/components/)
     * ou os icônes do font awesome (http://fortawesome.github.io/Font-Awesome/3.2.1/icons/)
     * @param  string $menu_name             nome do menu
     * @param  string $menu_href             Endereço que deve acessar
     * @param  int $menu_level_permission    Nível de permissão do menu
     * @return boolean verdadeiro caso sucesso ou falso caso ocorra alguma falha
     */
    public function updateMenu($menu_id, $menu_icon, $menu_name, $menu_href, $menu_level_permission) {
        $menu = Menu::findFirstByMenu_id($menu_id);
        $menu->menu_icon = $menu_icon;
        $menu->menu_name = $menu_name;
        $menu->menu_href = $menu_href;
        $menu->menu_level_permission = $menu_level_permission;
        $success = $menu->save();

        return $success;
    }
}
