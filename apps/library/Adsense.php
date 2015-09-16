<?php
/**
 * Class and Function List:
 * Function list:
 * - getService()
 * - getFirstprofileId()
 * - getEarnings()
 * Classes list:
 * - AdsenseController extends BaseController
 */
namespace Multiple\Library;

use Google_Client;
use Google_Service_AdSense;
use Google_Auth_AssertionCredentials;

class Adsense extends BaseController {

    public function getService($google_account_login, $google_account_key_file_name) {

        $client = new Google_Client();
        $service_account_email = $google_account_login;
        $key_file_location = FOLDER_PROJECT . "keys/" . $google_account_key_file_name;

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("PlutonAdsense");
        $service = new Google_Service_AdSense($client);

         // Read the generated client_secrets.p12 key.
        $key = file_get_contents($key_file_location);
        $cred = new Google_Auth_AssertionCredentials($service_account_email, array(
            Google_Service_Adsense::ADSENSE_READONLY
        ) , $key);
        $client->setAssertionCredentials($cred);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }

        return $service;
    }

    public function getEarnings($google_account_login, $google_account_key_file_name) {
        $adsense = AdsenseController::getService($google_account_login, $google_account_key_file_name);
        $optParams = array('metric' => array('earnings'), 'dimension' => 'date');
        $data = $adsense->reports->generate('2009-01-01', '2015-09-15', $optParams);
        var_dump($data);
    }
}
