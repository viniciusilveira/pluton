<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - newUserAction()
 * - editUserAction()
 * - deleteUserAction()
 * Classes list:
 * - SettingsController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Users,
    Multiple\Backend\Models\Blogs,
    Multiple\Backend\Models\UserType;

class SettingsController extends BaseController {

    /**
     * Carrega a tela principal do backend
     */
    public function indexAction() {

        //Inicia a sessão
        $this->session->start();

        /**
         * @todo:
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
        if ($this->session->get("user_id") != NULL) {
            $users = new Users();
            $user = $users->getUser($this->session->get("user_login"));

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

    public function addNewUserAction(){
        $user_name = $this->request->getPost('user_name');
        $user_email = $this->request->getPost('user_email');
        $user_login = $this->request->getPost('user_login');
        $user_type = $this->request->getPost('user_type');
        $user_img = $this->request->getPost('user_img');
        $user_passwd  = $this->request->getPost('user_passwd');

        $upload_img = $this->uploadImageAction($user_img, 128, 128, 3072);
        print_r($upload_img); die();
    }

    public function editUserAction() {
    }

    public function deleteUserAction() {
    }

    public function newUserAction() {
        $user_type = new UserType();
        $var['types'] = $user_type->getAllUserTypes();
        $this->view->setVars($var);
        $this->view->render('settings', 'newUser');
        // view/settings/newUser.phtml


    }

    /**
     * @todo: Action para upload de imagens para o servidor (Ainda não utilizado - Talvez seja movido para outra classe)
     * @param  file $file   imagem
     * @param  int $width   Largura máxima da imagem
     * @param  in $heigth   Altura máxima da imagem
     * @param  int $size    Tamanho máximo da imagem
     * @return string       Nome da imagem ou erro caso ocorroa algum.
     */
    public function uploadImageAction($file, $width, $heigth, $size) {

        // Verifica se o arquivo é uma imagem
        if (!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $file->getExtension())) {
            $error[1] = "Arquivo Inválido!";
        }

        // Pega as dimensões da imagem
        $dimensions = getimagesize($file->getTempName());

        // Verifica se a largura da imagem é maior que a largura permitida
        if ($dimensions[0] > $width) {
            $error[2] = "A largura da imagem não deve ultrapassar " . $width . " pixels!";
        }

        // Verifica se a altura da imagem é maior que a altura permitida
        if ($dimensions[1] > $heigth) {
            $error[3] = "Altura da imagem não deve ultrapassar " . $heigth . " pixels!";
        }

        // Verifica se o tamanho da imagem é maior que o tamanho permitido
        if ($file->getSize() > $size) {
            $error[4] = "A imagem deve ter no máximo " . $size / 1024 . "0 MB!";
        }
        if (count($error == 0)) {
            $this->session->start();
            /**
             * @todo: verificar possibilidade de buscar o user_login do banco de dados
             */
            $user_login = $this->session->get('user_login');

            $ext = $file->getExtension();
            $name_img = $user_login . $ext;
            if (!file_exists(FOLDER_PROJECT . 'public/img/users')) mkdir(FOLDER_PROJECT . 'public/img/users');
            $path_img = FOLDER_PROJECT . 'public/img/users/' . $name_img;
            $file->moveTo($path_img);
            return $name_img;
        }
        else {
            return $error;
        }
    }


}
