<?php
/**
 * Class and Function List:
 * Function list:
 * - facebook_count()
 * Classes list:
 * - FacebookController extends \
 */
namespace Multiple\Backend\Controllers;

class FacebookController extends \Phalcon\Mvc\Controller {

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
