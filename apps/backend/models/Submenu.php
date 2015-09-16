<?php

namespace Multiple\Backend\Models;

class Submenu extends \Phalcon\Mvc\Model{

	public function createSubmenu($menu_id, $submenu_icon, $submenu_name, $submenu_href, $submenu_order){
		$submenu = new Submenu();
		$submenu->menu_id = $menu_id;
		$submenu->submenu_icon = $submenu_icon;
		$submenu->submenu_name = $submenu_name;
		$submenu->submenu_href = $submenu_href;
		$submenu->submenu_order = $submenu_order;
		$return = $submenu->save();
		//var_dump($submenu->_errorMessages); die();
		return $return;
	}

	public function updateSubmenu($submenu_id, $submenu_icon, $submenu_name, $submenu_href, $submenu_order){
		$submenu = Submenu::findFirstBySubmenu_id($submenu_id);
		$submenu->submenu_icon = $submenu_icon;
		$submenu->submenu_name = $submenu_name;
		$submenu->submenu_href = $submenu_href;
		$submenu->submenu_order = $submenu_order;
		$return = $submenu->save();
		//var_dump($submenu->_errorMessages); die();
		return $return;
	}

}