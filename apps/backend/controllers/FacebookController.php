<?php
/**
 * Class and Function List:
 * Function list:
 * - facebook_count()
 * Classes list:
 * - FacebookController extends \
 */
namespace Multiple\Backend\Controllers;

/**
 * Classe responsável por coletar dados referentes ao facebook
 */
class FacebookController extends \Phalcon\Mvc\Controller {

	/**
	 * Conta a quantidade de likes de uma página
	 * @param  string $url nome da página a ser consultada
	 */
    function facebook_count($url) {

        // Query in FQL
        $fql = "SELECT share_count, like_count, comment_count ";
        $fql.= " FROM link_stat WHERE url = '$url'";

        $fqlURL = "https://api.facebook.com/method/fql.query?format=json&query=" . urlencode($fql);

        // Facebook Response is in JSON
        $response = file_get_contents($fqlURL);
        return json_decode($response, true);
    }
}
