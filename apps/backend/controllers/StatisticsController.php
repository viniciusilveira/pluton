<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * Classes list:
 * - StatisticsController extends BaseController
 */
namespace Multiple\Backend\Controllers;

use Multiple\Backend\Controllers\AnalyticsController;

use Multiple\Backend\Models\GoogleAccounts;

class StatisticsController extends BaseController {

    public function indexAction() {
        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $vars = $this->getUserLoggedInformation();
            $google_account = GoogleAccounts::findFirst();
        	$result_countries = AnalyticsController::getCountryOriginAccess($google_account->google_account_login, $google_account->google_account_key_file_name);
        	foreach($result_countries as $id => $country){
        		$countries[$id] = $country[0];
        		$sessions_country[$id] = $country[1];
        	}
        	$sessions = AnalyticsController::getAccessPerMonth($google_account->google_account_login, $google_account->google_account_key_file_name);
        	$vars['sessions'] = $sessions['sessions'];
            $vars['months'] = $sessions['months'];
            $vars['total_sessions'] = $sessions['total_sessions'];
            $vars['menus'] = $this->getSideBarMenus();
            $vars['countries'] = $countries;
            $vars['sessions_country'] = $sessions_country;
            $vars['pageviews'] = AnalyticsController::getPageViews($google_account->google_account_login, $google_account->google_account_key_file_name);
            $this->view->setVars($vars);
        }
    }
}
