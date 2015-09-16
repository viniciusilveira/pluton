<?php
/**
* Class and Function List:
* Function list:
* - createPlugin()
* - updatePlugin()
* Classes list:
* - Plugin extends \
*/
namespace Multiple\Backend\Models;

class Plugin extends \Phalcon\Mvc\Model {

    public function createPlugin($menu_id, $plugin_name) {
        $plugin = new Plugin();
        $plugin->menu_id = $menu_id;
        $plugin->plugin_name = $plugin_name;
        $return = $plugin->save();

        return $return;
    }

    public function updatePlugin($menu_id, $plugin_name) {
        $plugin = Plugin::findFirstByMenu_id($menu_id);
        $plugin->plugin_name = $plugin_name;

        $return = $plugin->save();

        return $return;
    }
}
