<?php

/**
 * Class and Function List:
 * Function list:
 * - onConstruct()
 * - indexAction()
 * - newUserAction()
 * - addNewUserAction()
 * - editUserAction()
 * - deleteUserAction()
 * - uploadImageAction()
 * Classes list:
 * - SettingsController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset;
use Multiple\Backend\Models\UserType, Multiple\Backend\Models\Users;

class SettingsController extends BaseController
{

    private $users;

    public function onConstruct() {
        $this->users = new Users;
    }

    /**
     * Carrega a tela principal do backend
     *  * @todo:
     * => Variáveis:
     * $blog (boolean) => true caso exista um blog, false caso não exista
     * $user_type (char) => Nível de permissão do usuário logado
     * $img_user (string) => caminho para imagem de usuário (caso exista)
     *      se não existir inserir caminho para imagem padrão
     *
     * => Funcionalidades:
     * google analitics => Verificar como integrar ao blog e criar relatórios/gráficos
     * redes sociais => Verificar como integrar ao blog
     * usuários online => Como contar a quantidade de usuários online? É possível pelo analitics?
     *
     * Verificar o que mais é necessário para index
     */
    public function indexAction() {

        //Inicia a sessão
        $this->session->start();

        if ($this->session->get("user_id") != NULL) {

            $user = $this->users->getUser($this->session->get("user_login"));

            $user_name = explode(" ", $user->user_name);

            //Array para envio de dados para a view a ser carregada
            $vars['user'] = $user_name[0];
            $vars['user_type_id'] = $user->user_type_id;
            $this->view->setVars($vars);
            $this->view->render('settings', 'index');
        }
        else {
            $this->view->pick('login/index');
        }
    }

    /**
     * Carrega o formulário de cadastro de usuário na tela
     * @return [type] [description]
     */
    public function newUserAction() {
        if ($this->request->getPost("user_id") != NULL) {
            $result = Users::findFirstByUser_id($this->request->getPost("user_id"));
            $vars['user']['user_id'] = $result->user_id;
            $vars['user']['user_name'] = $result->user_name;
            $vars['user']['user_login'] = $result->user_login;
            $vars['user']['user_email'] = $result->user_email;
            $vars['user']['user_type_id'] = $result->user_type_id;
        }
        $user_type = new UserType;
        $vars['types'] = $user_type->getAllUserTypes();
        $this->view->setVars($vars);
        $this->view->render('settings', 'newUser');
    }

    /**
     * [addNewUserAction description]
     */
    public function addNewUserAction() {
        $this->view->disable();
        $user_name = $this->request->getPost('user_name');
        $user_email = $this->request->getPost('user_email');
        $user_login = $this->request->getPost('user_login');
        $user_type_id = $this->request->getPost('user_type_id');
        $user_passwd = sha1(md5($this->request->getPost('user_passwd')));

        if (!$this->users->userExists($user_name, $user_login, $user_email)) {

            //Verifica se existe arquivo para upload, caso exista efetua o upload
            if ($this->request->hasFiles() == true) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    if ($file->getTempName() != NULL) {
                        $upload_img = $this->uploadImageAction($file, 200, 300, 3145728, $user_login);
                    }
                }
            }

            if (is_array($upload_img)) {
                $data = $upload_img;
                $data['success'] = false;
            }
            else {
                $data['success'] = Users::createUser($user_name, $user_email, $user_login, $user_passwd, $user_type_id, $upload_img, 1);
            }
        }
        else {
            $data['message'] = "Login ou senha informados já existe! Por favor verifique os dados informados e tente novamente!";
            $data['success'] = false;
        }

        echo json_encode($data);
    }

    /**
     * Busca todos os usuários do sistema e lista na tela
     */
    public function listUsersAction() {
        $vars['users'] = Users::find();
        $vars['success'] = true;
        $this->view->setVars($vars);
        $this->view->render("settings", "listUsers");
    }

    /**
     * [deleteUserAction description]
     * @return [type] [description]
     */
    public function deleteUserAction() {
    }

    /**
     * @todo: Action para upload de imagens para o servidor
     * @param  file $file   imagem
     * @param  int $width   Largura máxima da imagem
     * @param  in $heigth   Altura máxima da imagem
     * @param  int $size    Tamanho máximo da imagem
     * @return string       Nome da imagem ou erro caso ocorroa algum.
     */
    public function uploadImageAction($file, $width, $heigth, $size, $img_name) {

        // Pega as dimensões da imagem
        $dimensions = getimagesize($file->getTempName());

        // Verifica se o arquivo é uma imagem
        if (!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $file->getRealType())) {
            $data['message'] = "O Arquivo inserido não parece ser uma imagem!";
        }
        elseif ($dimensions[0] > $width) {

            // Verifica se a largura da imagem é maior que a largura permitida
            $data['message'] = "A largura da imagem não deve ultrapassar " . $width . " pixels!";
        }
        elseif ($dimensions[1] > $heigth) {

            // Verifica se a altura da imagem é maior que a altura permitida
            $data['message'] = "Altura da imagem não deve ultrapassar " . $heigth . " pixels!";
        }
        elseif ($file->getSize() > $size) {

            // Verifica se o tamanho da imagem é maior que o tamanho permitido
            $data['message'] = "A imagem deve ter no máximo " . $size / 1024 . "MB!";
        }
        else {

            //Caso não haja erros faz o upload da imagem e salva a mesma no servidor

            $ext = $file->getExtension();
            $name_img = $img_name . "." . $ext;

            //Verifica se a pasta public/img/users existe, se não existir, cria.
            if (!file_exists(FOLDER_PROJECT . 'public/img/users')) mkdir(FOLDER_PROJECT . 'public/img/users');

            $path_img = FOLDER_PROJECT . 'public/img/users/' . $name_img;
            $file->moveTo($path_img);
            return $name_img;
        }
        return $data;
    }
}
