<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Exception;

/**
 * Classe para conexão e configuração dos dados necessários para inicialização
 * do blog
 * 
 * OBSERVAÇÃO: Necessário extender a classe Injectable ao invés da Controllers para
 * ser possivel sobescrever o método __construct
 */
class SetupController extends \Phalcon\DI\Injectable {

    private $connection;
    private $config;
    private $user;
    private $blog;
    private $tables;

    /**
     * Construct necessário para iniciar objetos de outras classes
     */
    public function __construct() {
        
        $this->user = new \Multiple\Backend\Models\Users;
        $this->blog = new \Multiple\Backend\Models\Blogs;
        $this->tables = new \Multiple\Library\Tables;
    }

    public function indexAction() {
        // view/setup/index.phtml
    }

    /**
     * Verifica os dados do banco para saber se existe usuário e blog já criados.
     * @return string contendo o dado não criado no banco de dados, ou 'ok' caso
     * já esteja tudo criado
     */
    public function verifyDataBaseAction() {
        if (file_exists(FOLDER_PROJECT . 'apps/config/config.ini')) {
            if (!$this->user->verifyUserExistAction()) {
                return 'user';
            } elseif (!$this->blog->verifyBlogExistAction()) {
                return 'blog';
            } else {
                return 'ok';
            }
        } else {
            return 'file';
        }
    }

    public function databaseConfigAction() {
        // views/setup/databaseConfig.phtml
    }

    /**
     * Recebe os dados do banco de dados via post;
     * Cria o arquivo de configuração do banco de dados com os arquivos recebidos
     * Conecta com o banco de dados
     */
    public function databaseSettingsAction() {

        //Dados do banco de dados recebidos via POST;
        $database_name = $this->request->getPost('database_name');
        $database_user = $this->request->getPost('database_user');
        $database_passwd = $this->request->getPost('database_passwd');
        $database_host = $this->request->getPost('database_host');

        //Cria o arquivo de conexão com o banco de dados;
        $config_file = fopen(FOLDER_PROJECT . 'apps/config/config.ini', 'w') or die("Unable to open file!");
        $writing_file = "[database]\n";
        $writing_file .= "adapter  = Mysql\n";
        $writing_file .= "host     = {$database_host}\n";
        $writing_file .= "username = {$database_user}\n";
        $writing_file .= "password = {$database_passwd}\n";
        $writing_file .= "name     = {$database_name}\n";

        fwrite($config_file, $writing_file);
        fclose($config_file);

        //Seta a configuração do banco de dados.
        $this->config = new \Phalcon\Config\Adapter\Ini(FOLDER_PROJECT . 'apps/config/config.ini');

        //Cria um array com os dados do banco
        $db_conn = array(
            "host" => $this->config->database->host,
            "username" => $this->config->database->username,
            "password" => $this->config->database->password,
            "dbname" => $this->config->database->name,

        );
        $db_conn["persistent"] = false;

        //Efetua a conexão com o banco de dados
        try{
            $this->connection = new \Phalcon\Db\Adapter\Pdo\Mysql($db_conn);
            $this->createTablesAction();
            $data['connection'] = true;
            $data['message'] = 'Banco de dados conectado e configurado!';
            echo json_encode($data);
            //Die necessário para que a mensagem seja enviada. Sem ele o ajax não exibe a mensagem.
            die();
        }catch(\PDOException $e){
            unlink(FOLDER_PROJECT . 'apps/config/config.ini');
            $data['connection'] = false;
            $data['message'] = 'Erro ao conectar ao banco de dados! Por favor verifique se os dados informados
                                estão corretos e tente novamente!';
            echo json_encode($data);
            //Die necessário para que a mensagem seja enviada. Sem ele o ajax não exibe a mensagem.
            die();
        }
    }

    /**
     * Cria as tabelas necessárias para o funcionamento do sistema
     */
    public function createTablesAction() {

            $this->connection->tableExists('layouts') ? NULL : $this->tables->createTableLayouts($this->connection);
            $this->connection->tableExists('blogs') ? NULL : $this->tables->createTableBlogs($this->connection);
            $this->connection->tableExists('users') ? NULL : $this->tables->createTableUsers($this->connection);
            $this->connection->tableExists('users_blogs') ? NULL : $this->tables->createTableUsersBlogs($this->connection);
            $this->connection->tableExists('posts') ? NULL : $this->tables->createTablePosts($this->connection);
            $this->connection->tableExists('social_network') ? NULL : $this->tables->createTableSocialNetwork($this->connection);

    }

    public function newBlogAction() {
        // views/setup/newBlog.phtml
    }

    public function newUserAction() {
        // views/setup/newUser.phtml
    }


    /**
     * Cria o primeiro usuário do sistema (Super Administrador)
     */
    public function createSuperAdmin(){
        $user_name = $this->request->getPost('user_name');
        $user_email = $this->request->getPost('user_email');
        $user_login = $this->request->getPost('user_login');
        $user_passwd = $this->request->getPost('user_passwd');

        $this->user->createUser($user_name, $user_email, $user_login, $user_passwd, 'SA');
    }

}
