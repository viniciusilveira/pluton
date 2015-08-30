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
            //var_dump($google_account); die();
            if($google_account != NULL){
                $vars['google_account_login'] = $google_account->google_account_login;
                $vars['google_account_key_file_name'] = $google_account->google_account_key_file_name;
                $this->view->setVars($vars);
            }
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
        //remove o arquivo antigo e insere o novo
        unlink(FOLDER_PROJECT . $google_account->google_account_key_file_name);
        $p12_key->moveTo(FOLDER_PROJECT . $p12_key->getName());

        $data['success'] = GoogleAccounts::updateGoogleAccount($g_account, $p12_key->getName());

        echo json_encode($data);
    }

    public function registerFacebookAccountsAction() {
    }

    public function registerTwitterAccountsAction() {
    }

    public function ConfigureEmailAction() {
    }
}
