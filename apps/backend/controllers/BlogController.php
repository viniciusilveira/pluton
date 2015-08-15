<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - saveAction()
 * Classes list:
 * - BlogController extends BaseController
 */
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Models\Layouts;

class BlogController extends BaseController {

    public function indexAction() {
        $this->response->redirect(URL_PROJECT . '/editor?editor=true');
    }

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
