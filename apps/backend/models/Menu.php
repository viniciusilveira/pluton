<?php

namespace Multiple\Backend\Models;

class Menu extends \Phalcon\Mvc\Model{

	public function createMenu($menu_icon, $menu_name, $menu_href, $menu_level_permission, $submenu_div_name){
		$menu = new Menu();
		$menu->menu_icon = $menu_icon;
		$menu->menu_name = $menu_name;
		$menu->menu_href = $menu_href;
		$menu->menu_level_permission = $menu_level_permission;
		$menu->submenu_div_name = $submenu_div_name;
		$menu->save();

		return $menu->menu_id;
	}
}