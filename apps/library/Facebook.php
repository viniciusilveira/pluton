<?php
/**
 * Class and Function List:
 * Function list:
 * - facebookCount()
 * Classes list:
 * - Facebook extends BaseController
 */
namespace Multiple\Library;

/**
 * Classe responsável por coletar dados referentes ao facebook
 */
class Facebook {

    /**
     * Conta a quantidade de likes de uma página
     * @param  string $url nome da página a ser consultada
     */
    function facebookCount($url) {

        // Query in FQL
        $fql = "SELECT share_count, like_count, comment_count ";
        $fql.= " FROM link_stat WHERE url = '$url'";

        $fqlURL = "https://api.facebook.com/method/fql.query?format=json&query=" . urlencode($fql);

        // Facebook Response is in JSON
        $response = file_get_contents($fqlURL);
        return json_decode($response, true);
    }
}
