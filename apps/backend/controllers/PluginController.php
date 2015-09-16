<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - listPluginsAction()
 * - addPluginAction()
 * - updatePluginAction()
 * Classes list:
 * - PluginController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Phalcon\Http\Response;

use Multiple\Backend\Models\Menu;
use Multiple\Backend\Models\Submenu;
use Multiple\Backend\Models\Plugin;
use Multiple\Backend\Models\Users;

/**
 * Classe responsável pela manipulação de plugins no sistema
 */
class PluginController extends BaseController {

    /**
     * Carrega o Formulário para cadastro de um novo plugin
     */
    public function indexAction() {
        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $usr = Users::findFirstByUser_id($this->session->get("user_id"));
            if ($usr->user_type_id <= 2) {

                $vars = $this->getUserLoggedInformation();
                $vars['menus'] = $this->getSideBarMenus();

                if ($this->request->get("plugin_id") != NULL) {
                    $plugin = Plugin::findFirstByPlugin_id($this->request->get("plugin_id"))->toArray();
                    $menu = Menu::findFirstByMenu_id($plugin['menu_id'])->toArray();
                    $submenus = Submenu::findByMenu_id($plugin['menu_id'])->toArray();
                    $vars['submenu1'] = $submenus[0];
                    $vars['submenu2'] = $submenus[1];
                    $vars['plugin'] = $plugin;
                    $vars['menu'] = $menu;
                }
                $this->view->setVars($vars);
            }
            else {
                $this->response->redirect(URL_PROJECT . 'admin');
            }
        }
        else {
            $this->response->redirect(URL_PROJECT . 'admin');
        }
    }

    /**
     * Carrega tabela com uma lista dos plugins instalados
     */
    public function listPluginsAction() {
        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $vars = $this->getUserLoggedInformation();
            $vars['menus'] = $this->getSideBarMenus();
            $usr = Users::findFirstByUser_id($this->session->get("user_id"));
            if ($usr->user_type_id <= 2) {
                $plugins = Plugin::find()->toArray();
                foreach ($plugins as $id => $plugin) {
                    $menus[$id] = Menu::findFirstByMenu_id($plugin['menu_id'])->toArray();
                }
                $vars['menu'] = $menus;
                $vars['plugins'] = $plugins;
            }
        }

        $this->view->setVars($vars);
    }

    /**
     * Adiciona um novo plugin conforme dados recebidos via POST
     */
    public function addPluginAction() {
        $this->view->disable();
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
        if ($id_menu != 0 && $success) {
            $data['success'] = true;
        }
        else {
            $data['success'] = false;
        }

        echo json_encode($data);
    }

    /**
     * Atualiza um plugin conforme dados recebidos via POST
     */
    public function updatePluginAction() {
        $this->view->disable();
        $plugin_name = $this->request->getPost("plugin_name");

        $id_menu = $this->request->getPost("menu_id");
        $menu_name = $this->request->getPost("exibition_name");
        $menu_icon = $this->request->getPost("icon");
        $menu_url = $this->request->getPost("url");
        $menu_level_permission = $this->request->getPost("level_permission");

        $success = Menu::updateMenu($id_menu, $menu_icon, $menu_name, $menu_url, $menu_level_permission);

        $success = Plugin::updatePlugin($id_menu, $plugin_name);

        if (!empty($this->request->getPost("submenu1_name")) && $success) {
            $submenu_id = $this->request->getPost("submenu1_id");
            $submenu_name = $this->request->getPost("submenu1_name");
            $submenu_url = $this->request->getPost("submenu1_url");
            $submenu_icon = $this->request->getPost("submenu1_icon");
            if (empty($submenu_id)) {
                $success = Submenu::createSubmenu($id_menu, $submenu_icon, $submenu_name, $submenu_url, 1);
            }
            else {
                $success = Submenu::updateSubmenu($submenu_id, $submenu_icon, $submenu_name, $submenu_url, 1);
            }
        }

        if (!empty($this->request->getPost("submenu2_name")) && $success) {
            $submenu_id = $this->request->getPost("submenu2_id");
            $submenu_name = $this->request->getPost("submenu2_name");
            $submenu_url = $this->request->getPost("submenu2_url");
            $submenu_icon = $this->request->getPost("submenu2_icon");
            if (empty($submenu_id)) {
                $success = Submenu::createSubmenu($id_menu, $submenu_icon, $submenu_name, $submenu_url, 2);
            }
            else {
                $success = Submenu::updateSubmenu($submenu_id, $submenu_icon, $submenu_name, $submenu_url, 2);
            }
        }
        $data['success'] = $success;

        echo json_encode($data);
    }
}
