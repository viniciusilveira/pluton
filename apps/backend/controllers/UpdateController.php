<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - getVersions()
 * Classes list:
 * - UpdateController extends \
 */

namespace Multiple\Backend\Controllers;

class UpdateController extends \Phalcon\Mvc\Controller {

    public function indexAction() {

        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $file = fopen(FOLDER_PROJECT . "version", "r") or die("Unable to open file!");
            $version = fread($file, filesize(FOLDER_PROJECT . "version"));
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

            //print_r($vars); die();
            $this->view->setVars($vars);
            $this->view->render("update", "index");
        }
        else {
            $this->view->pick('login/index');
        }
    }

    public function getVersions() {
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/viniciusilveira/pluton/tags");
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
         // Set a user agent
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        // close cURL resource, and free up system resources
        curl_close($ch);

        return json_decode($response, true);
    }
}
