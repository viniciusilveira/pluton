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

/**
 * Classe responsável por manipular dados referentes aos plugins
 */
class Plugin extends \Phalcon\Mvc\Model {

    /**
     * Cria um novo plugin
     * @param  int $menu_id     id do menu ao qual o plugin se refere
     * @param  string $plugin_name nome do plugin
     * @return boolean              Verdadeiro caso sucesso ou falso caso ocorra alguma falha
     */
    public function createPlugin($menu_id, $plugin_name) {
        $plugin = new Plugin();
        $plugin->menu_id = $menu_id;
        $plugin->plugin_name = $plugin_name;
        $return = $plugin->save();

        return $return;
    }

    /**
     * Atualiza um plugin existente
     * @param  int $menu_id     id do menu ao qual o plugin se refere
     * @param  string $plugin_name nome do plugin
     * @return boolean              Verdadeiro caso sucesso ou falso caso ocorra alguma falha
     */
    public function updatePlugin($menu_id, $plugin_name) {
        $plugin = Plugin::findFirstByMenu_id($menu_id);
        $plugin->plugin_name = $plugin_name;

        $return = $plugin->save();

        return $return;
    }
}
