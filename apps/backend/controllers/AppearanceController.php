<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - saveAction()
 * Classes list:
 * - AppearanceController extends BaseController
 */
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Models\Layouts;

/**
 * Classe responsável pela edição da aparência do blog
 */
class AppearanceController extends BaseController {

    /**
     * Redireciona para a página inicial do blog permitindo a edição do mesmo
     */
    public function indexAction() {
        $this->session->start();
        $this->response->redirect(URL_PROJECT . 'editor?editor=true');
    }

    /**
     * Salva a alteração da parte do layout recebida via post
     */
    public function saveAction() {
        $this->view->disable();

        if ($this->request->getPost('id') != NULL && $this->request->getpost('content') != NULL) {
            $id = $this->request->getPost('id');

            $content = $this->request->getPost('content');

            if (Layouts::updateLayout($id, $content)) {

                //retorna sucesso
                echo json_encode(array(
                    'status' => 'ok'
                ));
            }
        }
    }
}
