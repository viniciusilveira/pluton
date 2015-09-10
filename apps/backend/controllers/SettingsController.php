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
 * Classes list:
 * - SettingsController extends BaseController
 */

namespace Multiple\Backend\Controllers;

//use Phalcon\Http\Response;
use Multiple\Backend\Models\GoogleAccounts;
use Multiple\Backend\Models\FacebookPages;
use Multiple\Backend\Models\TwitterAccounts;
use Multiple\Backend\Models\MailSettings;
use Multiple\Backend\Models\Blogs;

/**
 * Classe responsável por manipular os dados do google analytics
 */
class SettingsController extends BaseController {

    public function indexAction() {
        $this->session->start();
        if ($this->session->get("user_id") != NULL) {
            $vars = $this->getUserLoggedInformation();
            $google_account = GoogleAccounts::findFirst();
            if (!empty($google_account)) {
                $vars['google_account_login'] = $google_account->google_account_login;
                $vars['google_account_key_file_name'] = $google_account->google_account_key_file_name;
            }

            $fb_page = FacebookPages::findFirst();
            if ($fb_page != NULL) {
                $vars['fb_page_name'] = $fb_page->facebook_page_name;
            }

            $tw_account = TwitterAccounts::findFirst();
            if (!empty($tw_account)) {
                $vars['tw_account_app_id'] = $tw_account->twitter_account_app_id;
                $vars['tw_account_app_secret'] = $tw_account->twitter_account_app_secret;
                $vars['tw_account_username'] = $tw_account->twitter_account_username;
            }

            $preferences = Blogs::findFirst();
            if(!empty($preferences)){
                $vars['title'] = $preferences->blog_name;
                $vars['url'] = $preferences->blog_url;
            }
            $vars['menus'] = $this->getSideBarMenus();
            //Caso haja dados de conta a ser exibido seta as váriaveis para exibição na view
            if (!empty($vars)) $this->view->setVars($vars);

            $this->view->render("settings", "index");
        }
        else {
            $this->response->redirect(URL_PROJECT . "settings");
        }
    }

    /**
     * Recebe os dados da conta google informados para o usuário e salva/atualiza no banco de dados
     * @return json
     */
    public function registerGoogleAccountsApiAccessAction() {
        $this->view->disable();
        $g_account = $this->request->getPost('g_account');
        if ($this->request->hasFiles() == true) {
            foreach ($this->request->getUploadedFiles() as $file) {
                $p12_key = $file;
            }
        }
        $p12_key->moveTo(FOLDER_PROJECT . "keys/" . $p12_key->getName());
        $data['success'] = GoogleAccounts::createGoogleAccount($g_account, $p12_key->getName());

        echo json_encode($data);
    }

    public function updateGoogleAccountsApiAccessAction() {
        $this->view->disable();
        $g_account = $this->request->getPost('g_account');
        if ($this->request->hasFiles() == true) {
            foreach ($this->request->getUploadedFiles() as $file) {
                $p12_key = $file;
            }
        }

        $google_account = GoogleAccounts::findFirst();

        $data['success'] = GoogleAccounts::updateGoogleAccount($g_account, $p12_key->getName());
        if ($data['success']) {

            //remove o arquivo antigo e insere o novo
            unlink(FOLDER_PROJECT . "keys/" . $google_account->google_account_key_file_name);
            $p12_key->moveTo(FOLDER_PROJECT . "keys/" . $p12_key->getName());
        }
        echo json_encode($data);
    }

    public function registerFacebookPageNameAction() {
        $this->view->disable();

        $fb_page_name = $this->request->getPost("page_name");
        $data['success'] = FacebookPages::createFacebookPage($fb_page_name);
        echo json_encode($data);
    }

    public function updateFacebookPageNameAction() {
        $this->view->disable();

        $fb_page_name = $this->request->getPost("page_name");
        $data['success'] = FacebookAccounts::updateFacebookAccount($fb_page_name);
        echo json_encode($data);
    }

    public function registerTwitterAccountsApiAccessAction() {
        $this->view->disable();
        $tw_app_id = $this->request->getPost("app_id");
        $tw_app_secret = $this->request->getPost("app_secret");
        $tw_username = $this->request->getPost("username");
        $data['success'] = TwitterAccounts::createTwitterAccount($tw_app_id, $tw_app_secret, $tw_username);
        echo json_encode($data);
    }

    public function updateTwitterAccountsApiAccessAction() {
        $this->view->disable();
        $tw_app_id = $this->request->getPost("app_id");
        $tw_app_secret = $this->request->getPost("app_secret");
        $tw_username = $this->request->getPost("username");
        $data['success'] = TwitterAccounts::updateTwitterAccount($tw_app_id, $tw_app_secret, $tw_username);
        echo json_encode($data);
    }

    public function updatePreferencesAction(){
        $this->view->disable();
        $title_blog = $this->request->getPost("title_blog");
        $url_project = $this->request->getPost("url_project");
        $data['success'] = Blogs::updateBlog($title_blog, $url_project);

        echo json_encode($data);
    }
}
