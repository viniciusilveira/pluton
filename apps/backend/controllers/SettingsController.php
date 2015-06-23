<?php

/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * Classes list:
 * - SettingsController extends \
 */

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Users,
    Phalcon\Http\Response;

class SettingsController extends \Phalcon\Mvc\Controller {

    public function indexAction() {

        $response = new Response();
        session_start();
        /**
         * @todo: 
         * => Variáveis:
         * $blog (boolean) => true caso exista um blog, false caso não exista
         * $permissao (char) => Nível de permissão do usuário logado
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
        if ($_SESSION['user_login'] != NULL) {
            $users = new Users();
            $user = explode(" ", $users->getUser($_SESSION['user_login']));
            $vars['user'] = $user[0];

            $this->view->setVars($vars);
            $this->view->render('settings', 'index');
        } else {
            $response->redirect("login/index");
        }
    }

}
