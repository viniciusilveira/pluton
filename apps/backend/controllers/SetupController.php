<?php
/**
 * Class and Function List:
 * Function list:
 * - indexAction()
 * - databaseConfigAction()
 * - newBlogAction()
 * - installAction()
 * - verifyInstalation()
 * - verifyDataBaseAction()
 * - databaseSettingsAction()
 * - connectDatabase()
 * - createTables()
 * - createUsersTypes()
 * - createPostsStatus()
 * - installPlutonAction()
 * Classes list:
 * - SetupController extends BaseController
 */
namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Users;
use Multiple\Backend\Models\Blogs;
use Multiple\Backend\Models\Layouts;
use Multiple\Backend\Models\UserType;
use Multiple\Backend\Models\PostStatus;
use Multiple\Backend\Models\UserBlog;
use Multiple\Library\Tables;

/**
 * Classe para conexão e configuração dos dados necessários para inicialização
 * do blog
 *
 */
class SetupController extends BaseController {

    public $connection;
    private $config;
    private $user;
    private $blog;
    private $layout;
    private $userType;
    private $tables;

    public function indexAction() {

        // view/setup/index.phtml


    }

    /**
     * Carrega a view para inserção dos dados de conexão com o banco de dados
     */
    public function databaseConfigAction() {

        // views/setup/databaseConfig.phtml


    }

    public function newBlogAction() {

        // views/setup/newBlog.phtml


    }

    public function installAction() {

        // views/setup/newUser.phtml


    }

    /**
     * Verifica a instalação do sistema, caso esteja tudo ok retorna a string 'ok',
     * caso contrário retorna o que falta ser configurado.
     */
    public function verifyInstalation() {
        if (file_exists(FOLDER_PROJECT . 'apps/config/config.ini')) {
            $return = !Users::verifyUsersExistAction() ? 'user' : 'ok';
        }
        else {
            $return = 'file';
        }

        return $return;
    }

    /**
     * Verifica se o banco de dados está configurado corretamente, caso não esteja,
     * efetua as configurações necessárias.
     * @return string contendo o dado não criado no banco de dados, ou 'ok' caso
     * já esteja tudo criado
     */
    public function verifyDataBaseAction() {

        if (file_exists(FOLDER_PROJECT . 'apps/config/config.ini')) {

            $connect = SetupController::connectDatabase();
            if (!$connect['connection']) {
                $return = 'connect';
            }
            else {
                $return = !Users::verifyUsersExistAction() ? 'user' : 'ok';
            }
        }
        else {
            $return = 'file';
        }

        return $return;
    }

    /**
     * Recebe os dados do banco de dados via post;
     * Cria o arquivo de configuração do banco de dados com os arquivos recebidos
     * Conecta com o banco de dados
     */
    public function databaseSettingsAction() {

        //Informa que a action não possui nenhuma view para exibição
        $this->view->disable();

        //Dados do banco de dados recebidos via POST;

        $database_name = $this->request->getPost('database_name');
        $database_user = $this->request->getPost('database_user');
        $database_passwd = $this->request->getPost('database_passwd');
        $database_host = $this->request->getPost('database_host');

        //Cria o arquivo de conexão com o banco de dados;
        if (!file_exists(FOLDER_PROJECT . 'apps/config/config.ini')) {
            !is_dir(FOLDER_PROJECT . 'apps/config/') ? mkdir(FOLDER_PROJECT . 'apps/config/') : NULL;
            if ($config_file = fopen(FOLDER_PROJECT . 'apps/config/config.ini', 'w')) {
                $writing_file = "[database]\n";
                $writing_file.= "adapter  = Mysql\n";
                $writing_file.= "host     = {$database_host}\n";
                $writing_file.= "username = {$database_user}\n";
                $writing_file.= "password = {$database_passwd}\n";
                $writing_file.= "name     = {$database_name}\n";

                fwrite($config_file, $writing_file);
                fclose($config_file);

                $data = $this->connectDatabase();
            }
            else {
                $data['success'] = false;
                $data['message'] = "Impossível acessar o arquivo de configuração (pluton/apps/config/config.ini). Verifique as permissões de acesso da pasta e tente novamente!";
            }
            echo json_encode($data);
        }
    }

    /**
     * Configura e executa a conexão com o banco de dados
     * @return bool true caso conecte com sucesso ou false caso ocorra algum erro
     */
    private function connectDatabase() {
        //$this->view->disable();

        //Seta a configuração do banco de dados.
        $this->config = new \Phalcon\Config\Adapter\Ini(FOLDER_PROJECT . 'apps/config/config.ini');

        //Cria um array com os dados do banco
        $db_conn = array(
            "host" => $this->config->database->host,
            "username" => $this->config->database->username,
            "password" => $this->config->database->password,
            "dbname" => $this->config->database->name,
            "charset" => 'utf8'
        );
        $db_conn["persistent"] = false;

        //Efetua a conexão com o banco de dados
        try {
            $this->connection = new \Phalcon\Db\Adapter\Pdo\Mysql($db_conn);
            SetupController::createTables();
            $data['connection'] = true;
            $data['message'] = "Banco de dados conectado e configurado!";
            return $data;
        }
        catch(\PDOException $e) {
            unlink(FOLDER_PROJECT . 'apps/config/config.ini');
            $data['connection'] = false;
            $data['message'] = "Ocorreu um problema ao conectar com o banco de dados. Verifique os dados informados e tente novamente!";
            $data['log'] = $e;
            return $data;
        }
    }

    /**
     * Cria as tabelas necessárias para o funcionamento do sistema
     */
    private function createTables() {
        //$this->view->disable();
        if(!$this->connection->tableExists('layouts')) Tables::createTableLayouts($this->connection);
        if(!$this->connection->tableExists('blogs')) Tables::createTableBlogs($this->connection);
        if(!$this->connection->tableExists('user_type')) Tables::createTableUserType($this->connection);
        if(!$this->connection->tableExists('users')) Tables::createTableUsers($this->connection);
        if(!$this->connection->tableExists('users_blogs')) Tables::createTableUsersBlogs($this->connection);
        if(!$this->connection->tableExists('categories')) Tables::createTableCategories($this->connection);
        if(!$this->connection->tableExists('post_status')) Tables::createTablePostStatus($this->connection);
        if(!$this->connection->tableExists('posts')) Tables::createTablePosts($this->connection);
        if(!$this->connection->tableExists('post_categorie')) Tables::createTablePostCategories($this->connection);
        if(!$this->connection->tableExists('google_accounts')) Tables::createTableGoogleAccounts($this->connection);
        if(!$this->connection->tableExists('facebook_pages')) Tables::createTableFacebookPages($this->connection);
        if(!$this->connection->tableExists('twitter_accounts')) Tables::createTableTwitterAccounts($this->connection);
    }

    /**
     * Cria os tipos de usuários no sistema
     * @return boolean true caso sucesso, false caso ocorra algum erro!
     */
    private function createUsersTypes() {
        $success = UserType::createUserType('SUPER ADMINISTRADOR', 'SA');
        $success = !$success ? $success : UserType::createUserType('ADMINISTRADOR', 'A');
        $success = !$success ? $success : UserType::createUserType('EDITOR', 'E');
        $success = !$success ? $success : UserType::createUserType('AUTOR', 'AT');
        $success = !$success ? $success : UserType::createUserType('COLABORADOR', 'C');
        return $success;
    }

    private function createPostsStatus() {

        $success = PostStatus::createPostStatus("Publicado");
        $success = !$success ? $success : PostStatus::createPostStatus("Pendente");
        $success = !$success ? $success : PostStatus::createPostStatus("Rascunho");
        $success = !$success ? $success : PostStatus::createPostStatus("Lixo");

        return $success;
    }

    /**
     * Efetua a "instalação" do sistema; Criando o usuário Super-Administrador e o blog
     * @return [type] [description]
     */
    public function installPlutonAction() {

        //Informa que a action não possui nenhuma view para exibição
        $this->view->disable();

        $blog_name = $this->request->getPost('blog_name');
        $user_name = $this->request->getPost('user_name');
        $user_email = $this->request->getPost('user_email');
        $user_login = $this->request->getPost('user_login');
        $user_passwd = sha1(md5($this->request->getPost('user_passwd')));

        /**
         * Insere os dados necessários no banco de dados para utilização inicial do sistema
         */
        try {

            $success = $this->createUsersTypes();
            $success = $success ? $this->createPostsStatus() : false;
            $success = $success ? Layouts::createLayout($this->request->getPost('blog_name')) : false;

            $success = $success ? Blogs::createBlog($blog_name) : false;
            $blog = Blogs::findFirst();

            $success = $success ? Users::createUser($user_name, $user_email, $user_login, $user_passwd, 1, NULL, $blog->blog_id) : false;
            $user = Users::findFirst();

            $success = $success ? UserBlog::createUserBlog($user->user_id, $blog->blog_id) : false;

            $data['message'] = $success ? 'Sistema Instalado Com sucesso!' : 'Ocorreu um erro durante a instalação. Por favor tente novamente';
            $data['success'] = $success;
            echo json_encode($data);
        }
        catch(\PDOException $e) {
            $data['success'] = false;
            $data['message'] = "Ocorreu um erro: " . $e;

            /**
             * @todo: apagar todos os dados inseridos no banco
             */

            echo json_encode($data);
        }
    }
}
