<?php
/**
 * Class and Function List:
 * Function list:
 * - onConstruct()
 * - indexAction()
 * - databaseConfigAction()
 * - newBlogAction()
 * - installAction()
 * - errorAction()
 * - verifyDataBaseAction()
 * - databaseSettingsAction()
 * - connectDatabase()
 * - createTablesAction()
 * - createUsersTypes()
 * - installPlutonAction()
 * Classes list:
 * - SetupController extends BaseController
 */
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Models as Models;

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

    /**
     * Construct necessário para iniciar objetos de outras classes
     */
    public function onConstruct() {
        $this->user = new Models\Users;
        $this->blog = new Models\Blogs;
        $this->layout = new Models\Layouts;
        $this->userType = new Models\UserType;
        $this->tables = new \Multiple\Library\Tables;
    }

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

    public function errorAction() {
        die("Erro conexão");
    }

    /**
     * Verifica os dados do banco para saber se existe usuário e blog já criados.
     * @return string contendo o dado não criado no banco de dados, ou 'ok' caso
     * já esteja tudo criado
     */
    public function verifyDataBaseAction() {

        if (file_exists(FOLDER_PROJECT . 'apps/config/config.ini')) {
            $connect = $this->connectDatabase();
            if (!$connect['connection']) {
                $return = 'connect';
            }
            else {
                $this->createTablesAction();
                $return = !$this->user->verifyUsersExistAction() ? 'user' : 'ok';
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
            $config_file = fopen(FOLDER_PROJECT . 'apps/config/config.ini', 'w') or die("Unable to open file!");
            $writing_file = "[database]\n";
            $writing_file.= "adapter  = Mysql\n";
            $writing_file.= "host     = {$database_host}\n";
            $writing_file.= "username = {$database_user}\n";
            $writing_file.= "password = {$database_passwd}\n";
            $writing_file.= "name     = {$database_name}\n";

            fwrite($config_file, $writing_file);
            fclose($config_file);

            $data = $this->connectDatabase();

            echo json_encode($data);
        }
    }

    /**
     * Configura e executa a conexão com o banco de dados
     * @return bool true caso conecte com sucesso ou false caso ocorra algum erro
     */
    public function connectDatabase() {

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
            $this->createTablesAction();
            $data['connection'] = true;
            $data['message'] = "Banco de dados conectado e configurado!";
            return $data;
        }
        catch(\PDOException $e) {
            unlink(FOLDER_PROJECT . 'apps/config/config.ini');
            $data['connection'] = false;
            $data['message'] = "Ocorreu um problema ao conectar com o banco de dados. Verifique os dados informados!<br/>" . $e;
            return $data;
        }
    }

    /**
     * Cria as tabelas necessárias para o funcionamento do sistema
     */
    public function createTablesAction() {

        $this->connection->tableExists('layouts') ? NULL : $this->tables->createTableLayouts($this->connection);
        $this->connection->tableExists('blogs') ? NULL : $this->tables->createTableBlogs($this->connection);
        $this->connection->tableExists('user_type') ? NULL : $this->tables->createTableUserType($this->connection);
        $this->connection->tableExists('users') ? NULL : $this->tables->createTableUsers($this->connection);
        $this->connection->tableExists('users_blogs') ? NULL : $this->tables->createTableUsersBlogs($this->connection);
        $this->connection->tableExists('posts') ? NULL : $this->tables->createTablePosts($this->connection);
        $this->connection->tableExists('social_network') ? NULL : $this->tables->createTableSocialNetwork($this->connection);
    }

    /**
     * Cria os tipos de usuários no sistema
     * @return boolean true caso sucesso, false caso ocorra algum erro!
     */
    public function createUsersTypes() {
        $success = $this->userType->createUserType('SUPER ADMINISTRADOR', 'SA');
        $success = !$success ? $success : $this->userType->createUserType('ADMINISTRADOR', 'A');
        $success = !$success ? $success : $this->userType->createUserType('EDITOR', 'E');
        $success = !$success ? $success : $this->userType->createUserType('AUTOR', 'AT');
        $success = !$success ? $success : $this->userType->createUserType('COLABORADOR', 'C');
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
            $success = $this->user->createUser($user_name, $user_email, $user_login, $user_passwd, 1);
            $success = $success ? $data['success'] = $this->layout->createLayout() : false;
            $success = $success ? $data['success'] = $this->blog->createBlog($blog_name) : false;
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
            $this->user->deleteAdminUser();
            echo json_encode($data);
        }
    }
}
