<?php

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * Classes list:
 * - AnalyticsController extends BaseController
 */

namespace Multiple\Backend\Controllers;

//use Phalcon\Http\Response;
use Multiple\Backend\Models\GoogleAccounts;
use Multiple\Backend\Models\FacebookAccounts;

/**
 * Classe responsável por manipular os dados do google analytics
 */
class SecurityController extends BaseController{

    private $ga;

    public function onConstruct() {

        //$analytics = Analytics::findFirst();
        //$this->ga = new gapi($analytics->analytics_login, $analytics->analytics_password);

    }

    public function indexAction() {
    	$this->session->start();
    	if($this->session->get("user_id")!= NULL){
            $google_account = GoogleAccounts::findFirst();
            if($google_account != NULL){
                $vars['google_account_login'] = $google_account->google_account_login;
                $vars['google_account_key_file_name'] = $google_account->google_account_key_file_name;
            }

            $fb_account = FacebookAccounts::findFirst();
            if($fb_account != NULL){
                $vars['fb_account_app_id'] = $fb_account->facebook_account_app_id;
                $vars['fb_account_app_secret'] = $fb_account->facebook_account_app_secret;
            }

            //Caso haja dados de conta a ser exibido seta as váriaveis para exibição na view
            (!empty($google_account) || !empty($fb_account)) ? $this->view->setVars($vars) : NULL;

    		$this->view->render("security", "index");
    	} else{
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
        $p12_key->moveTo(FOLDER_PROJECT . $p12_key->getName());

        $data['success'] = GoogleAccounts::createGoogleAccount($g_account, $p12_key->getName());

        echo json_encode($data);
    }

    public function updateGoogleAccountsApiAccessAction(){
        $this->view->disable();
        $g_account = $this->request->getPost('g_account');
        if ($this->request->hasFiles() == true) {
            foreach ($this->request->getUploadedFiles() as $file) {
                 $p12_key = $file;
            }
        }

        $google_account = GoogleAccounts::findFirst();

        $data['success'] = GoogleAccounts::updateGoogleAccount($g_account, $p12_key->getName());
        if($data['success']){
            //remove o arquivo antigo e insere o novo
            unlink(FOLDER_PROJECT . $google_account->google_account_key_file_name);
            $p12_key->moveTo(FOLDER_PROJECT . $p12_key->getName());
        }
        echo json_encode($data);
    }

    public function registerFacebookAccountsApiAccessAction() {
        $this->view->disable();

        $fb_app_id = $this->request->getPost("app_id");
        $fb_app_secret = $this->request->getPost("app_secret");
        $data['success'] = FacebookAccounts::createFacebookAccount($fb_app_id, $fb_app_secret);
        echo json_encode($data);
    }

    public function updateFacebookAccountsApiAccessAction(){
        $this->view->disable();

        $fb_app_id = $this->request->getPost("app_id");
        $fb_app_secret = $this->request->getPost("app_secret");
        $data['success'] = FacebookAccounts::updateFacebookAccount($fb_app_id, $fb_app_secret);
        echo json_encode($data);
    }

    public function registerTwitterAccountsAction() {
    }

    public function ConfigureEmailAction() {
    }
}
