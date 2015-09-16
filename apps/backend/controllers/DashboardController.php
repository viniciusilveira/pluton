<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - newUserAction()
 * - verifyPermissionEditedUser()
 * - addNewUserAction()
 * - listUsersAction()
 * - updateUserAction()
 * - ActiveOrdeactiveUserAction()
 * - uploadImage()
 * - getImgName()
 * - getApiSocialsData()
 * Classes list:
 * - DashboardController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Phalcon\Http\Response;

use Multiple\Library\Mail;

use Multiple\Library\Analytics;
use Multiple\Backend\Controllers\FacebookSdkController;
use Multiple\Backend\Controllers\TwitterSdkController;
use Multiple\Backend\Controllers\SettingsController;
use Multiple\Backend\Controllers\UpdateController;

use Multiple\Backend\Models\UserType;
use Multiple\Backend\Models\Users;
use Multiple\Backend\Models\UserBlog;
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
     * Carrega o formulário de cadastro de usuário na tela
     */
    public function newUserAction() {

        $this->session->start();
        $user = Users::findFirstByUser_id($this->session->get("user_id"));

        //Caso o usuário logado seja administrador ou super administrador OU o usuário logado solicitou a edição do próprio perfil carrega a tela
        if (($user->user_id != NULL && $user->user_type_id <= 2) || (!empty($this->request->get("user_id")) && $this->request->get("user_id") == $user->user_id)) {
            $vars = $this->getUserLoggedInformation();

            if ($this->request->get("user_id") != NULL) {
                $result = Users::findFirstByUser_id($this->request->get("user_id"));

                if (!$this->verifyPermissionEditedUser($result, Users::findFirstByUser_id($this->session->get("user_id")))) {
                    $this->response->redirect(URL_PROJECT . "admin");
                }
                else {
                    $vars['user_edit']['user_id'] = $result->user_id;
                    $vars['user_edit']['user_name'] = $result->user_name;
                    $vars['user_edit']['user_login'] = $result->user_login;
                    $vars['user_edit']['user_email'] = $result->user_email;
                    $vars['user_edit']['user_type_id'] = $result->user_type_id;
                    $vars['user_edit']['user_img'] = $result->user_img;
                    $vars['user_edit']['user_active'] = $result->user_active;
                    $vars['edit_user'] = true;
                    $vars['not_disable'] = ($result->user_id == $this->session->get("user_id")) ? true : false;
                }
            }
            else {
                $vars['edit_user'] = false;
            }
            $user_type = new UserType;
            $vars['types'] = $user_type->getAllUserTypes();
            $vars['menus'] = $this->getSideBarMenus();

            //var_dump($vars); die();
            $this->view->setVars($vars);
            $this->view->render('dashboard', 'newUser');
        }
        else {
             // Caso contrário redireciona para página inicial
            $this->response->redirect(URL_PROJECT . 'admin');
        }
    }

    /**
     * Verifica se o usuário logado possui permissão para editar o usuário solicitado
     * @param  Phalcon\Mvc\Model\Resultset $user_edit   Objeto de resultado com dados de usuário
     * @param  Phalcon\Mvc\Model\Resultset $user_logged Objeto de resultado com dados de usuário
     * @return boolean              Retorna verdadeiro caso possua permissão ou false caso contrário
     */
    private function verifyPermissionEditedUser($user_edit, $user_logged) {
        if ($user_logged->user_type_id == 1) {
            return true;
        }
        elseif ($user_logged->user_type_id == 2 && $user_edit->user_type_id != 1) {
            return true;
        }
        elseif ($user_logged->user_type_id > 2 && ($user_logged->user_id == $user_edit->user_id)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Recebe os dados de um novo usuário via POST e adiciona no banco de dados
     */
    public function addNewUserAction() {
        $this->view->disable();

        $user_name = $this->request->getPost('user_name');
        $user_email = $this->request->getPost('user_email');
        $user_login = $this->request->getPost('user_login');
        $user_type_id = $this->request->getPost('user_type_id');
        $user_passwd = sha1(md5($this->request->getPost('user_passwd')));

        if (!Users::userExists($user_name, $user_login, $user_email)) {

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
            $data['message'] = "Login ou email informados já existe! Por favor verifique os dados informados e tente novamente!";
            $data['success'] = false;
        }

        echo json_encode($data);
    }

    /**
     * Busca todos os usuários cadastrados no sistema e os exibe em uma tabela
     */
    public function listUsersAction() {
        $this->session->start();

        if ($this->session->get("user_id") != NULL) {

            $vars = $this->getUserLoggedInformation();
            $user_loged = Users::findFirstByUser_id($this->session->get("user_id"));

            //var_dump($user_loged->user_type); die();
            if ($user_loged->user_type_id == 2) {
                $vars['users'] = Users::find(array(
                    "conditions" => "user_type_id >= :user_type_id:",
                    "bind" => array(
                        "user_type_id" => $user_loged->user_type_id
                    ) ,
                    "order" => "user_name DESC"
                ));
            }
            else {
                $vars['users'] = Users::find();
            }
            $vars['menus'] = $this->getSideBarMenus();
            $vars['success'] = true;
            $this->view->setVars($vars);
            $this->view->render("dashboard", "listUsers");
        }
        else {
            $this->response->redirect('login/index');
        }
    }

    /**
     * Atualiza os dados do usuário pelos dados informados via POST
     */
    public function updateUserAction() {
        $this->view->disable();

        $user_id = $this->request->getPost('user_id');

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
     * Altera o status de um usuário, se estiver ativo, desativa e se estiver desativado, ativa
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

    /**
     * Utiliza o método BaseController::uid() para gerar um nome para a imagem de perfil a ser salva.
     * @return string nome da imagem
     */
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

    /**
     * Busca no banco de dados informações sobre acessos ao blog (Google Analytics),
     * seguidores do Twitter (Twitter API) e curtidas da página do facebook informada;
     * Caso os dados sobre tais APIS e redes sociais não estejam configurados, todos os valores são retornados como 0
     * @return array Array Contendo informações sobre Google Analytics, Twitter e Facebook
     */
    public function getApiSocialsData() {

        //Dados do google analytics
        $google_account = GoogleAccounts::findFirst();
        if (!empty($google_account)) {

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
        if (!empty($tw_account)) {
            $bearer_token = TwitterSdkController::generateBearerToken($tw_account->twitter_account_app_id, $tw_account->twitter_account_app_secret);
            $data_twitter = TwitterSdkController::getLookupTwitterProfileBlog($bearer_token, $tw_account->twitter_account_username);

            $vars['followers_count'] = $data_twitter[0]['followers_count'];
        }
        else {
            $vars['followers_count'] = 0;
        }

        //Dados do Facebook
        $fb_page = FacebookPages::findFirst();
        if (!empty($fb_page)) {
            $facebook = FacebookController::facebook_count("https://www.facebook.com/" . $fb_page->facebook_page_name);
            $vars['facebook_likes'] = $facebook[0]['like_count'];
        }
        else {
            $vars['facebook_likes'] = 0;
        }

        return $vars;
    }
}
