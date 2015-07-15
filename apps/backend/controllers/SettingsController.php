<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * Classes list:
 * - SettingsController extends BaseController
 */

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Users, Multiple\Backend\Models\Blogs;

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
         * Verificar por que a validação de Sessão aparentemente não está funcionando.
         * Verificar o que mais é necessário para index
         */
        if ($this->session->get("user_id") != NULL) {
            $users = new Users();
            $blogs = new Blogs();
            $user = $users->getUser($this->session->get("user_login"));
            $user_name = explode(" ", $user->user_name);

            //Array para envio de dados para a view a ser carregada
            $vars['user'] = $user_name[0];
            $vars['user_type'] = $user->user_type;
            $vars['blog_exists'] = $blogs->verifyBlogExistAction();

            $this->view->setVars($vars);
            $this->view->render('settings', 'index');
        }
        else {
            $this->view->pick('login/index');
        }
    }

    public function newUserAction(){
        // view/settings/newUser.phtml
    }
}
