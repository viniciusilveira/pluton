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

class Menu extends \Phalcon\Mvc\Model {

    public function createMenu($menu_icon, $menu_name, $menu_href, $menu_level_permission) {
        $menu = new Menu();
        $menu->menu_icon = $menu_icon;
        $menu->menu_name = $menu_name;
        $menu->menu_href = $menu_href;
        $menu->menu_level_permission = $menu_level_permission;
        $menu->save();

        return $menu->menu_id;
    }

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
