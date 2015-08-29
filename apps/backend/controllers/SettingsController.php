<?php

/**
 * Class and Function List:
 * Function list:
 * - onConstruct()
 * - indexAction()
 * - newUserAction()
 * - addNewUserAction()
 * - listUsersAction()
 * - updateUserAction()
 * - ActiveOrdeactiveUserAction()
 * - uploadImageAction()
 * - getImgName()
 * Classes list:
 * - SettingsController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Controllers\AnalyticsController;

use Multiple\Backend\Models\UserType;
use Multiple\Backend\Models\Users;
use Multiple\Backend\Models\UserBlog;
use Multiple\Backend\Models\Posts;
use Multiple\Backend\Models\GoogleAccounts;

class SettingsController extends BaseController{

    private $users;

    public function onConstruct() {
        $this->users = new Users;
    }

    /**
     * Carrega a tela principal do backend
     *  * @todo:
     * => Variáveis:
     * $blog (boolean) => true caso exista um blog, false caso não exista
     *
     * redes sociais => Verificar como integrar ao blog
     * usuários online => Como contar a quantidade de usuários online? É possível pelo analitics?
     *
     * Verificar o que mais é necessário para index
     */

    public function indexAction() {

        //$users_online = AnalyticsController::getUsersOnline($analytics);
        //var_dump($users_online);


        //Inicia a sessão
        $this->session->start();

        if ($this->session->get("user_id") != NULL) {

            $user = $this->users->getUser($this->session->get("user_login"));

            $user_name = explode(" ", $user->user_name);
            $posts = Posts::findByPost_status_id(1);
            $vars['total_posts'] = count($posts);
            //Array para envio de dados para a view a ser carregada
            $vars['user'] = $user_name[0];
            $vars['user_type_id'] = $user->user_type_id;
            $vars['user_img'] = $user->user_img;

            //Dados do google analytics
            $data_analytics = AnalyticsController::getAccessPerMonth();
            $vars['sessions'] = $data_analytics['sessions'];
            $vars['months'] = $data_analytics['months'];
            $vars['total_sessions'] = $data_analytics['total_sessions'];
            $posts = Posts::find(array("conditions" => "post_status_id = :status:", "order" => "post_date_posted DESC", "limit" => 15, "bind" => array("status" => 1),));
            foreach ($posts as $post) {
                $post_content[$post->post_id] = substr(strip_tags($post->post_content), 0, 500) . "...";
            }

            //$this->printArray($post_content); die();
            $vars['posts'] = $posts;
            $vars['post_content'] = $post_content;
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
            $vars['user']['user_img'] = $result->user_img;
            $vars['user']['user_active'] = $result->user_active;
            $vars['edit_user'] = true;
        }
        else {
            $vars['edit_user'] = false;
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
                        $upload_img = $this->uploadImage($file, 500, 500, 3145728, $user_login);
                    }
                }
            }

            if (is_array($upload_img)) {
                $data = $upload_img;
                $data['success'] = false;
            }
            else {
                $data['success'] = Users::createUser($user_name, $user_email, $user_login, $user_passwd, $user_type_id, $upload_img, 1);
                $user = Users::findFirstByUser_login($user_login);
                $data['success'] = UserBlog::createUserBlog($user->user_id, 1);
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

    public function updateUserAction() {
        $this->view->disable();

        $user_id = $this->request->getPost('user_id');

        //var_dump($user); die();

        //Verifica se existe arquivo para upload, caso exista efetua o upload
        if ($this->request->hasFiles() == true) {
            foreach ($this->request->getUploadedFiles() as $file) {
                if ($file->getTempName() != NULL) {
                    $upload_img = $this->uploadImage($file, 500, 500, 3145728, $user->user_img);
                }
            }
        }

        if (is_array($upload_img)) {
            $data = $upload_img;
            $data['success'] = false;
        }
        else {
            try {
                $user->user_img = $upload_img;

                //Altera os valores recebidos pela consulta para os valores recebidos via POST.
                $user_name = $this->request->getPost('user_name');
                $user_login = $this->request->getPost('user_login');
                $user_email = $this->request->getPost('user_email');
                $user_type_id = $this->request->getPost('user_type_id');
                if ($this->request->getPost('user_passwd') != NULL) $user_passwd = sha1(md5($this->request->getPost('user_passwd')));
                $data['success'] = Users::updateUser($user_id, $user_name, $user_email, $user_login, $user_passwd, $user_type_id, $upload_img, 1);
                $data['message'] = $data['success'] ? NULL : "Ocorreu um erro ao salvar os dados. Por favor tente novamente";
            }
            catch(PDO\Exception $e) {
                $data['success'] = false;
                $data['message'] = 'Ocorreu um erro ao salvar os dados. Por favor tente novamente';
            }
        }
        echo json_encode($data);
    }

    /**
     * [deleteUserAction description]
     * @return [type] [description]
     */
    public function ActiveOrdeactiveUserAction() {
        $this->view->disable();

        $user_id = intval($this->request->getPost('user_id'));
        $data['success'] = Users::ActiveOrdeactiveUser($user_id);
        $data['message'] = 'Usuário Atualizado!';
        echo json_encode($data);
    }

    /**
     * @todo: Action para upload de imagens para o servidor
     * @param  file $file   imagem
     * @param  int $width   Largura máxima da imagem
     * @param  in $heigth   Altura máxima da imagem
     * @param  int $size    Tamanho máximo da imagem
     * @return string       Nome da imagem ou erro caso ocorroa algum.
     */
    private function uploadImage($file, $width, $heigth, $size, $name_img = NULL) {

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
            if ($name_img == NULL) {
                $name_img = $this->getImgName() . "." . $file->getExtension();
            }

            //Verifica se a pasta public/img/users existe, se não existir, cria.
            if (!file_exists(FOLDER_PROJECT . 'public/img/users')) mkdir(FOLDER_PROJECT . 'public/img/users');

            $path_img = FOLDER_PROJECT . 'public/img/users/' . $name_img;
            $file->moveTo($path_img);
            return $name_img;
        }
        return $data;
    }

    public function getImgName() {
        $img_name = $this->uid();
        $result = Users::findFirstByuser_img($img_name);
        if (empty($result)) {
            return $img_name;
        }
        else {
            $this->getImgName();
        }
    }
}
