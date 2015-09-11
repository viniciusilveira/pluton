<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - listPluginsAction()
 * - addPlugin()
 * - updatePlugin()
 * Classes list:
 * - PluginController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Menu;
use Multiple\Backend\Models\Submenu;
use Multiple\Backend\Models\Plugin;

class PluginController extends BaseController {

    public function indexAction() {
        $vars['menus'] = $this->getSideBarMenus();
        $vars+= $this->getUserLoggedInformation();
        $this->view->setVars($vars);
    }

    public function listPluginsAction() {
        $vars['menus'] = $this->getSideBarMenus();
        $vars+= $this->getUserLoggedInformation();
        $this->view->setVars($vars);
    }

    public function addPluginAction() {
        $plugin_name = $this->request->getPost("plugin_name");
        $menu_name = $this->request->getPost("exibition_name");
        $menu_icon = $this->request->getPost("icon");
        $menu_url = $this->request->getPost("url");
        $menu_level_permission = $this->request->getPost("level_permission");

        $id_menu = Menu::createMenu($menu_icon, $menu_name, $menu_url, $menu_level_permission);

        Plugin::createPlugin($id_menu, $plugin_name);
        $success = true;
        if (!empty($this->request->getPost("submenu1_name"))) {
            $submenu_name = $this->request->getPost("submenu1_name");
            $submenu_url = $this->request->getPost("submenu1_url");
            $submenu_icon = $this->request->getPost("submenu1_icon");
            $success = Submenu::createSubmenu($id_menu, $submenu_icon, $submenu_name, $submenu_url, 1);
        }

        if (!empty($this->request->getPost("submenu2_name")) && $success) {
            $submenu_name = $this->request->getPost("submenu2_name");
            $submenu_url = $this->request->getPost("submenu2_url");
            $submenu_icon = $this->request->getPost("submenu2_icon");
            $success = Submenu::createSubmenu($id_menu, $submenu_icon, $submenu_name, $submenu_url, 2);
        }
        if($id_menu != 0 && $success){
            return true;
        } else{
            return false;
        }
    }

    public function updatePluginAction() {
    }
}
