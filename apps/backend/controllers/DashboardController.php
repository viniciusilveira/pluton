<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - getApiSocialsData()
 * Classes list:
 * - DashboardController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Phalcon\Http\Response;

use Multiple\Library\Analytics;
use Multiple\Library\Facebook;
use Multiple\Library\TwitterSdk;

use Multiple\Backend\Controllers\SettingsController;

use Multiple\Backend\Models\Posts;
use Multiple\Backend\Models\GoogleAccounts;
use Multiple\Backend\Models\TwitterAccounts;
use Multiple\Backend\Models\FacebookPages;

/**
 * Classe responsável por manipular as operações do usuário na área administrativa
 */
class DashboardController extends BaseController {

    /**
     * Carrega a tela principal do backend
     */
    public function indexAction() {

        $this->session->start();

        if ($this->session->get("user_id") != NULL) {

            $posts = Posts::findByPost_status_id(1);
            $vars = $this->getUserLoggedInformation();
            $vars+= $this->getApiSocialsData();

            //Busca as últimas 15 postagens
            $posts = Posts::find(array(
                "conditions" => "post_status_id = :status:",
                "order" => "post_date_posted DESC",
                "limit" => 15,
                "bind" => array(
                    "status" => 1
                ) ,
            ));

            //Conta o total de postagens existentes;
            $vars['total_posts'] = count($posts);

            //Cria uma prévia do conteúdo da postagem
            foreach ($posts as $post) {
                $post_content[$post->post_id] = substr(strip_tags($post->post_content) , 0, 500) . "...";
            }
            $vars['posts'] = $posts;
            $vars['post_content'] = $post_content;
            $vars['menus'] = $this->getSideBarMenus();

            $this->view->setVars($vars);
            $this->view->render('dashboard', 'index');
        }
        else {
            $this->response->redirect(URL_PROJECT . "admin");
        }
    }

    /**
     * Busca no banco de dados informações sobre acessos ao blog (Google Analytics),
     * seguidores do Twitter (Twitter API) e curtidas da página do facebook informada;
     * Caso os dados sobre tais APIS e redes sociais não estejam configurados, todos os valores são retornados como 0
     * @return array Array Contendo informações sobre Google Analytics, Twitter e Facebook
     */
    public function getApiSocialsData() {

        //Dados do google analytics
        $google_account = GoogleAccounts::findFirst();
        $vars['analytics_active'] = $google_account->google_analytics_active;

        if (!empty($google_account) && ($google_account->google_analytics_active)) {
            $data_analytics = Analytics::getAccessPerMonth($google_account->google_account_login, $google_account->google_account_key_file_name);
            $vars['sessions'] = $data_analytics['sessions'];
            $vars['months'] = $data_analytics['months'];
            $vars['total_sessions'] = $data_analytics['total_sessions'];
        }
        else {
            $vars['total_sessions'] = 0;
            $vars['months'] = $this->mountArrayMonths();
        }

        //Dados do Twitter
        $tw_account = TwitterAccounts::findFirst();
        $vars['tw_active'] = $tw_account->twitter_active;
        if (!empty($tw_account) && $tw_account->twitter_active) {
            $bearer_token = TwitterSdk::generateBearerToken($tw_account->twitter_account_app_id, $tw_account->twitter_account_app_secret);
            $data_twitter = TwitterSdk::getLookupTwitterProfileBlog($bearer_token, $tw_account->twitter_account_username);

            $vars['followers_count'] = $data_twitter[0]['followers_count'];
        }
        else {
            $vars['followers_count'] = 0;
        }

        //Dados do Facebook
        $fb_page = FacebookPages::findFirst();
        $vars['fb_active'] = $fb_page->facebook_active;
        if (!empty($fb_page) && $fb_page->facebook_active) {
            $facebook = Facebook::facebookCount("https://www.facebook.com/" . $fb_page->facebook_page_name);
            $vars['facebook_likes'] = $facebook[0]['like_count'];
        }
        else {
            $vars['facebook_likes'] = 0;
        }

        return $vars;
    }
}
