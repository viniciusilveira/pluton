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

        if ($this->request->getPost('id') != NULL && $this->request->getpost('content')!= NULL) {
            $id = $this->request->getPost('id');
            $content = $this->request->getPost('id');
            $layout = Layouts::findFirst();
            switch ($id) {
                case 'title':
                    $layout->title = $content;
                    $layout->save();
                break;
                case 'subtitle':
                    $layout->subtitle = $content;
                    $layout->save();
                case 'navbar':
                    $layout->navbar = $content;
                    $layout->save();
                break;
                case 'lateral_bar':
                    $layout->lateral_bar = $content;
                    $layout->save();
                break;
                case 'search_bar':
                    $layout->search_bar = $content;
                    $layout->save();
                break;
                default:
                break;
            }

            //retorna sucesso
            echo json_encode(array(
                'status' => 'ok'
            ));
        }
        else {
            echo json_encode(array(
                'status' => 'false'
            ));
        }
    }
}
