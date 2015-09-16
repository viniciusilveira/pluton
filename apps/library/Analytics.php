<?php
/**
 * Class and Function List:
 * Function list:
 * - index()
 * - getService()
 * - getFirstprofileId()
 * - getResults()
 * - getTotalSessions()
 * - getRealTimeInformation()
 * - getAccessPerMonth()
 * - getCountryOriginAccess()
 * - getPageViews()
 * Classes list:
 * - Analytics extends \
 */
namespace Multiple\Library;

use Google_Client, Google_Service_Analytics, Google_Auth_AssertionCredentials;

class Analytics extends \Phalcon\Mvc\Controller {

    public function index() {
    }

    public function getService($google_account_login, $google_account_key_file_name) {

        // Creates and returns the Analytics service object.

        // Load the Google API PHP Client Library.
        //require_once 'google-api-php-client/src/Google/autoload.php';

        // Use the developers console and replace the values with your
        // service account email, and relative location of your key file.
        $service_account_email = $google_account_login;
        $key_file_location = FOLDER_PROJECT . "keys/" . $google_account_key_file_name;

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("PlutonAnalytics");
        $analytics = new Google_Service_Analytics($client);

        // Read the generated client_secrets.p12 key.
        $key = file_get_contents($key_file_location);
        $cred = new Google_Auth_AssertionCredentials($service_account_email, array(
            Google_Service_Analytics::ANALYTICS_READONLY
        ) , $key);
        $client->setAssertionCredentials($cred);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }

        return $analytics;
    }

    function getFirstprofileId(&$analytics) {

        // Get the user's first view (profile) ID.

        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();
                }
                else {
                    throw new Exception('No views (profiles) found for this user.');
                }
            }
            else {
                throw new Exception('No properties found for this user.');
            }
        }
        else {
            throw new Exception('No accounts found for this user.');
        }
    }

    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
    function getResults(&$analytics, $profileId, $initial, $final) {

        return $analytics->data_ga->get('ga:' . $profileId, $initial, $final, 'ga:sessions');
    }

    public function getTotalSessions($result) {
        if (count($result->getRows()) > 0) {

            // Get the profile name.
            $profileName = $result->getProfileInfo()->getProfileName();

            // Get the entry for the first entry in the first row.
            $rows = $result->getRows();
            $sessions = $rows[0][0];

            // Print the results.
            return $sessions;
        }
        else {
            return 0;
        }
    }

    /**
     * A funcionalidade de tempo real do google analytics ainda está em fase Beta e só é possível utiliza-la
     * sendo aprovado para o teste da funcionalidade;
     * Caso consiga a aprovação vocẽ pode chamar este método na action index da classee DashboardController
     * @param  [type] $analytics [description]
     * @return [type]            [description]
     */
    public function getRealTimeInformation($analytics) {
        $optParams = array(
            'dimensions' => 'rt:medium'
        );

        try {
            $results = $analytics->data_realtime->get('ga:66668523', 'rt:activeUsers', $optParams);

            return $results;
        }
        catch(apiServiceException $e) {

            $error = $e->getMessage();
            return $error;
        }
    }

    public function getAccessPerMonth($google_account_login, $google_account_key_file_name) {
        $arr_months = $this->mountArrayMonths();
        $analytics = Analytics::getService($google_account_login, $google_account_key_file_name);
        $profileId = Analytics::getFirstprofileId($analytics);
        $month = date('m');
        for ($m = 0;$m + 1 <= $month;$m++) {
            $initial = $m < 10 ? date('Y') . '-0' . ($m + 1) . '-01' : date('Y') . '-' . $m . '-01';

            $final = date("Y-m-t", strtotime($initial));
            $result = Analytics::getResults($analytics, $profileId, $initial, $final);
            $sessions[$m] = Analytics::getTotalSessions($result);
            $total_sessions+= $sessions[$m];
            $months[$m] = $arr_months[$m];

            //echo "initial: " . $initial . " final: " . $final . " sessions: " . $sessions[$m] . " <br> ";


        }
        $return['sessions'] = $sessions;
        $return['months'] = $months;
        $return['total_sessions'] = $total_sessions;
        return $return;
    }

    public function getCountryOriginAccess($google_account_login, $google_account_key_file_name) {
        $analytics = Analytics::getService($google_account_login, $google_account_key_file_name);
        $profileId = Analytics::getFirstprofileId($analytics);
        $result = $analytics->data_ga->get('ga:' . $profileId, '2009-01-01', date('Y-m-d') , 'ga:sessions', array(
            'dimensions' => 'ga:country',
            'sort' => 'ga:country'
        ));

        return $result->getRows();
    }

    public function getPageViews($google_account_login, $google_account_key_file_name) {
        $analytics = Analytics::getService($google_account_login, $google_account_key_file_name);
        $profileId = Analytics::getFirstprofileId($analytics);
        $result = $analytics->data_ga->get('ga:' . $profileId, '2009-01-01', date('Y-m-d') , 'ga:pageviews');
        return $result->getRows() [0][0];
    }
}
