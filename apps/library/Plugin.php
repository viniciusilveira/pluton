<?php

namespace Multiple\Library;

class Plugin{

	private $di;

	/**
	 * Action para tela de instalação de plugin
	 * @return [type] [description]
	 */
	public function indexAction(){

	}


	/**
	 * Action que instala plugins.
	 * @return [type] [description]
	 */
	public function installAction(){

	}

	/**
	 * [addMenu description]
	 * @param [type] $menu_name    [description]
	 * @param [type] $submenu_open [description]
	 * @param [type] $controller   [description]
	 * @param [type] $action       [description]
	 */
	public function addMenu($menu_name, $submenu_open = NULL, $permission_level, $controller = NULL, $action = NULL){

	}

	/**
	 * [addSubMenu description]
	 * @param [type] $menu_id      [description]
	 * @param [type] $submenu_name [description]
	 * @param [type] $controller   [description]
	 * @param [type] $action       [description]
	 */
	public function addSubMenu($menu_id, $submenu_name, $controller, $action){

	}



}