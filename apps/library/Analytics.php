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

/**
 * Classe responsável pelo gerenciamento das funcionalidades do Google Analytics
 */
class Analytics {

    /**
     * Cria um objeto do tipo Google_Service_Analytics
     * @param  string $google_account_login         login da conta google
     * @param  string $google_account_key_file_name Nome do arquivo chave para acesso a API do google
     * @return Google_Service_Analytics             Objeto do tipo Google_Service_Analytics
     */
    private function getService($google_account_login, $google_account_key_file_name) {

        $service_account_email = $google_account_login;
        $key_file_location = FOLDER_PROJECT . "keys/" . $google_account_key_file_name;

        $client = new Google_Client();
        $client->setApplicationName("PlutonAnalytics");
        $analytics = new Google_Service_Analytics($client);

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

    /**
     * Busca o id do primeiro perfil cadastrado na conta google
     * @param  Google_Service_Analytics &$analytics objeto do tipo Google_Service_Analytics
     * @return int si perfil
     */
    private function getFirstprofileId(&$analytics) {

        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            $properties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                $profiles = $analytics->management_profiles->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    return $items[0]->getId();
                }
            }
        }
        else {
            return -1;
        }
    }

    /**
     * Retorna o total de sessões
     * @param  Google_Service_Analytics &$analytics objeto do tipo Google_Service_Analytics
     * @param  id $profileId  id od profile do usuário
     * @param  date $initial    Data inicial dos resultados
     * @param  date $final    Data final dos resultados
     * @return array array     Total de sessões
     */
    function getResults(&$analytics, $profileId, $initial, $final) {

        return $analytics->data_ga->get('ga:' . $profileId, $initial, $final, 'ga:sessions');
    }

    private function getTotalSessions($result) {
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
     * Busca informações de acesso em tempo real
     * *A funcionalidade de tempo real do google analytics ainda está em fase Beta e só é possível utiliza-la
     * sendo aprovado para o teste da funcionalidade;
     * Caso consiga a aprovação vocẽ pode chamar este método na action index da classee DashboardController
     * @param  Google_Service_Analytics &$analytics objeto do tipo Google_Service_Analytics
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

    /**
     * Retorna os acessos únicos por mês, desde janeiro do ano corrente até o mês atual
     * @param  string $google_account_login         Login da conta google
     * @param  string $google_account_key_file_name Nome do arquivo chave para acesso a API do google
     * @return array contendo informações sobre sessões únicas
     */
    public function getAccessPerMonth($google_account_login, $google_account_key_file_name) {
        $arr_months = $this->mountArrayMonths();
        $analytics = Analytics::getService($google_account_login, $google_account_key_file_name);
        $profileId = Analytics::getFirstprofileId($analytics);
        $month = date('m');
        for ($m = 0;$m + 1 <= $month;$m++) {
            $initial = $m + 1 < 10 ? date('Y') . '-0' . ($m + 1) . '-01' : date('Y') . '-' . ($m + 1) . '-01';

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

    /**
     * Quantidade de acessos de cada país
     * @param  string $google_account_login         Login da conta google
     * @param  string $google_account_key_file_name Nome do arquivo chave para acesso a API do google
     * @return array contendo informações sobre sessões e país de origem
     */
    public function getCountryOriginAccess($google_account_login, $google_account_key_file_name) {
        $analytics = Analytics::getService($google_account_login, $google_account_key_file_name);
        $profileId = Analytics::getFirstprofileId($analytics);
        $result = $analytics->data_ga->get('ga:' . $profileId, '2009-01-01', date('Y-m-d') , 'ga:sessions', array(
            'dimensions' => 'ga:country',
            'sort' => 'ga:country'
        ));

        return $result->getRows();
    }

    /**
     * Busca as Visualizações de páginas do blog
     * @param  string $google_account_login         Login da conta google
     * @param  string $google_account_key_file_name Nome do arquivo chave para acesso a API do google
     * @return array contendo informações sobre visualizações de página
     */
    public function getPageViews($google_account_login, $google_account_key_file_name) {
        $analytics = Analytics::getService($google_account_login, $google_account_key_file_name);
        $profileId = Analytics::getFirstprofileId($analytics);
        $result = $analytics->data_ga->get('ga:' . $profileId, '2009-01-01', date('Y-m-d') , 'ga:pageviews');
        return $result->getRows() [0][0];
    }
}
