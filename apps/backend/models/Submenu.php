<?php
/**
* Class and Function List:
* Function list:
* - createSubmenu()
* - updateSubmenu()
* Classes list:
* - Submenu extends \
*/
namespace Multiple\Backend\Models;

/**
 * Classe responsável por manipular os dados referentes aos submenus
 */
class Submenu extends \Phalcon\Mvc\Model {

	/**
	 * Cria um novo submenu
	 * @param  int $menu_id       id do menu ao qual o submenu se referência
	 * @param  string $submenu_icon  nome do novo icone, pode ser referente a glyphicons do bootstrap(http://getbootstrap.com/components/)
     * ou os icônes do font awesome (http://fortawesome.github.io/Font-Awesome/3.2.1/icons/)
	 * @param  string $submenu_name  Nome do novo submenu
	 * @param  string $submenu_href  Endereço que deve acessar
	 * @param  int $submenu_order Ordem de exibição dos submenus
	 * @return boolean                Verdadeiro caso sucesso ou falso caso ocorra algum erro
	 */
    public function createSubmenu($menu_id, $submenu_icon, $submenu_name, $submenu_href, $submenu_order) {
        $submenu = new Submenu();
        $submenu->menu_id = $menu_id;
        $submenu->submenu_icon = $submenu_icon;
        $submenu->submenu_name = $submenu_name;
        $submenu->submenu_href = $submenu_href;
        $submenu->submenu_order = $submenu_order;
        $return = $submenu->save();

        return $return;
    }

    /**
     * Atualiza um submenu
     * @param  [type] $submenu_id    id do submenu a ser atualizado
     * @param  string $submenu_icon  nome do novo icone, pode ser referente a glyphicons do bootstrap(http://getbootstrap.com/components/)
     * ou os icônes do font awesome (http://fortawesome.github.io/Font-Awesome/3.2.1/icons/)
	 * @param  string $submenu_name  Nome do novo submenu
	 * @param  string $submenu_href  Endereço que deve acessar
	 * @param  int $submenu_order Ordem de exibição dos submenus
	 * @return boolean                Verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function updateSubmenu($submenu_id, $submenu_icon, $submenu_name, $submenu_href, $submenu_order) {
        $submenu = Submenu::findFirstBySubmenu_id($submenu_id);
        $submenu->submenu_icon = $submenu_icon;
        $submenu->submenu_name = $submenu_name;
        $submenu->submenu_href = $submenu_href;
        $submenu->submenu_order = $submenu_order;
        $return = $submenu->save();

        return $return;
    }
}
