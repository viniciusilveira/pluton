<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - registerGoogleAccountsApiAccessAction()
 * - updateGoogleAccountsApiAccessAction()
 * - registerFacebookPageNameAction()
 * - updateFacebookPageNameAction()
 * - registerTwitterAccountsApiAccessAction()
 * - updateTwitterAccountsApiAccessAction()
 * - updatePreferencesAction()
 * Classes list:
 * - SettingsController extends BaseController
 */

namespace Multiple\Backend\Controllers;

//use Phalcon\Http\Response;
use Multiple\Backend\Models\GoogleAccounts;
use Multiple\Backend\Models\FacebookPages;
use Multiple\Backend\Models\TwitterAccounts;
use Multiple\Backend\Models\Blogs;
use Multiple\Backend\Models\Users;

/**
 * Classe responsável por manipular as configurações e opções do sistema
 */
class SettingsController extends BaseController {

    /**
     * Carrega a tela inicial de configurações
     */
    public function indexAction() {
        $this->session->start();
        $user = Users::findFirstByUser_id($this->session->get("user_id"));

        if ($user->user_type_id <= 2) {
            $vars = $this->getUserLoggedInformation();

            //Busca informações da conta google
            $google_account = GoogleAccounts::findFirst();
            if (!empty($google_account)) {
                $vars['google_account_login'] = $google_account->google_account_login;
                $vars['google_account_key_file_name'] = $google_account->google_account_key_file_name;
                $vars['google_analytics_script'] = $google_account->google_analytics_script;
            }

            //Busca informações da página do facebook
            $fb_page = FacebookPages::findFirst();
            if ($fb_page != NULL) {
                $vars['fb_page_name'] = $fb_page->facebook_page_name;
            }

            //Busca informações do twitter
            $tw_account = TwitterAccounts::findFirst();
            if (!empty($tw_account)) {
                $vars['tw_account_app_id'] = $tw_account->twitter_account_app_id;
                $vars['tw_account_app_secret'] = $tw_account->twitter_account_app_secret;
                $vars['tw_account_username'] = $tw_account->twitter_account_username;
            }

            //Busca as preferências do blog
            $preferences = Blogs::findFirst();
            if (!empty($preferences)) {
                $vars['title'] = $preferences->blog_name;
                $vars['url'] = $preferences->blog_url;
                $vars['mail'] = $preferences->blog_mail;
                $vars['blog_about'] = $preferences->blog_about;
                $vars['menus'] = $this->getSideBarMenus();
            }

            //Caso haja dados de conta a ser exibido seta as váriaveis para exibição na view
            if (!empty($vars)) $this->view->setVars($vars);

            $this->view->render("settings", "index");
        }
        else {
            $this->response->redirect(URL_PROJECT . "admin");
        }
    }

    /**
     * Insere os dados recebidos da conta google via post no banco de dados
     */
    public function registerGoogleAccountsApiAccessAction() {
        $this->view->disable();
        $g_account = $this->request->getPost('g_account');
        if ($this->request->hasFiles() == true) {
            foreach ($this->request->getUploadedFiles() as $file) {
                $p12_key = $file;
            }
        }

        //var_dump($_POST); die();
        $g_script_analytics = $this->request->getPost("analytics_script");
        $p12_key->moveTo(FOLDER_PROJECT . "keys/" . $p12_key->getName());
        $data['success'] = GoogleAccounts::createGoogleAccount($g_account, $p12_key->getName() , $g_script_analytics);

        echo json_encode($data);
    }

    /**
     * Atualiuza os dados da conta google no banco de dados
     */
    public function updateGoogleAccountsApiAccessAction() {
        $this->view->disable();
        $g_account = $this->request->getPost('g_account');
        if ($this->request->hasFiles() == true) {
            foreach ($this->request->getUploadedFiles() as $file) {
                $p12_key = $file;
            }
        }

        $g_script_analytics = $this->request->getPost("analytics_script");

        $google_account = GoogleAccounts::findFirst();

        $data['success'] = GoogleAccounts::updateGoogleAccount($g_account, $p12_key->getName() , $g_script_analytics);

        if ($data['success']) {

            //remove o arquivo antigo e insere o novo
            unlink(FOLDER_PROJECT . "keys/" . $google_account->google_account_key_file_name);
            $p12_key->moveTo(FOLDER_PROJECT . "keys/" . $p12_key->getName());
        }
        echo json_encode($data);
    }

    /**
     * Insere os dados da página do facebook no banco de dados
     */
    public function registerFacebookPageNameAction() {
        $this->view->disable();

        $fb_page_name = $this->request->getPost("page_name");
        $data['success'] = FacebookPages::createFacebookPage($fb_page_name);
        echo json_encode($data);
    }

    /**
     * Atualiza os dados da página do facebook no banco de dados
     */
    public function updateFacebookPageNameAction() {
        $this->view->disable();

        $fb_page_name = $this->request->getPost("page_name");
        $data['success'] = FacebookPages::updateFacebookPage($fb_page_name);

        echo json_encode($data);
    }

    /**
     * Insere os dados do twitter no banco de dados
     */
    public function registerTwitterAccountsApiAccessAction() {
        $this->view->disable();
        $tw_app_id = $this->request->getPost("app_id");
        $tw_app_secret = $this->request->getPost("app_secret");
        $tw_username = $this->request->getPost("username");
        $data['success'] = TwitterAccounts::createTwitterAccount($tw_app_id, $tw_app_secret, $tw_username);
        echo json_encode($data);
    }

    /**
     * Atualiza os dados do twitter no banco de dados
     */
    public function updateTwitterAccountsApiAccessAction() {
        $this->view->disable();
        $tw_app_id = $this->request->getPost("app_id");
        $tw_app_secret = $this->request->getPost("app_secret");
        $tw_username = $this->request->getPost("username");
        $data['success'] = TwitterAccounts::updateTwitterAccount($tw_app_id, $tw_app_secret, $tw_username);
        echo json_encode($data);
    }

    /**
     * Atualiza as preferências do sistema
     * @return [type] [description]
     */
    public function updatePreferencesAction() {
        $this->view->disable();
        $title_blog = $this->request->getPost("title_blog");
        $url_project = $this->request->getPost("url_project");
        $mail_project = $this->request->getPost("mail_project");
        $mail_password = $this->request->getPost("mail_password");
        $blog_about = $this->request->getPost("blog_about");
        $data['success'] = Blogs::updateBlog($title_blog, $url_project, $mail_project, $mail_password, $blog_about);

        echo json_encode($data);
    }
}
