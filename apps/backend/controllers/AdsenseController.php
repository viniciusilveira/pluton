<?php
/**
 * Class and Function List:
 * Function list:
 * - getService()
 * Classes list:
 * - AdsenseController extends BaseController
 */
namespace Multiple\Backend\Controllers;

use Google_Client;
use Google_Service_AdSense;

class AdsenseController extends BaseController {

    public function getService($google_account_login) {

        $client = new Google_Client();
        $client->addScope('https://www.googleapis.com/auth/adsense.readonly');
        $client->setAccessType('offline');

        // Be sure to replace the contents of client_secrets.json with your developer
        // credentials.
        $client->setAuthConfigFile(FOLDER_PROJECT . 'keys/client_secrets.json');

        // Create service.
        $service = new Google_Service_AdSense($client);

        return $service;
    }
}
