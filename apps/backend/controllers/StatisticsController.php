<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * Classes list:
 * - StatisticsController extends BaseController
 */
namespace Multiple\Backend\Controllers;

use Multiple\Library\Analytics;

use Multiple\Backend\Models\GoogleAccounts;

/**
 * Classe responsável por exibir as estastistivas do sistema
 */
class StatisticsController extends BaseController {

    /**
     * Carrega a tela inicial, exibe gráficos informando sessões e localização de acessos
     */
    public function indexAction() {
        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $vars = $this->getUserLoggedInformation();
            $vars['menus'] = $this->getSideBarMenus();
            $vars['months'] = $sessions['months'];

            $google_account = GoogleAccounts::findFirst();
            if($google_account->google_analytics_active){
                $result_countries = Analytics::getCountryOriginAccess($google_account->google_account_login, $google_account->google_account_key_file_name);
                foreach ($result_countries as $id => $country) {
                    $countries[$id] = $country[0];
                    $sessions_country[$id] = $country[1];
                }
                $sessions = Analytics::getAccessPerMonth($google_account->google_account_login, $google_account->google_account_key_file_name);
                $vars['sessions'] = $sessions['sessions'];
                $vars['total_sessions'] = $sessions['total_sessions'];

                $vars['countries'] = $countries;
                $vars['sessions_country'] = $sessions_country;
                $vars['pageviews'] = Analytics::getPageViews($google_account->google_account_login, $google_account->google_account_key_file_name);
            } else{
                $vars['sessions'] = 0;
                $vars['total_sessions'] = 0;
                $vars['pageviews'] = 0;
            }

            $this->view->setVars($vars);
        }
    }
}
