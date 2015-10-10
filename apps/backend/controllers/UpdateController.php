<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - getVersions()
 * Classes list:
 * - UpdateController extends BaseController
 */

namespace Multiple\Backend\Controllers;

/**
 * Classe responsável pela consulta da existência de novas atualizações do sistema
 */
class UpdateController extends BaseController {

    /**
     * Carrega uma view que exibe se o sistema está atualizado
     */
    public function indexAction() {

        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $file = fopen(FOLDER_PROJECT . "version", "r") or die("Unable to open file!");
            $version = fread($file, filesize(FOLDER_PROJECT . "version"));
            $vars = $this->getUserLoggedInformation();
            fclose($file);
            $tags = $this->getVersions();

            //echo $version . "==" . $tags[0]['name']; die();
            if ($version < $tags[0]['name']) {
                $vars['version_update'] = $tags[0]['name'];
                $vars['new_version'] = true;
                $vars['link_new_version'] = $tags[0]['zipball_url'];
            }
            else {
                $vars['new_version'] = false;
            }
            $vars['version_install'] = $version;
            $vars['link_project'] = "https://github.com/viniciusilveira/pluton";
            $vars['menus'] = $this->getSideBarMenus();

            $this->view->setVars($vars);
            $this->view->render("update", "index");
        }
        else {
            $this->view->pick('login/index');
        }
    }

    /**
     * Busca todas as tags do repositório do Projeto
     */
    private function getVersions() {
        $ch = curl_init();

        // Seta a url e as propriedades
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/viniciusilveira/pluton/tags");
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        // fecha a cURL e libera os recursos para o sistema
        curl_close($ch);

        //retorna o resultado em formato de array
        return json_decode($response, true);
    }
}
