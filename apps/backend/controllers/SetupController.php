<?php
namespace Multiple\Backend\Controllers;

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
     * Seta o arquivo de configuração e chama as Actions responsáveis pela conexão
     * com o banco de dados e criação das tabelas necessárias
     */
    public function databaseSettings(){
        $this->config = new \Phalcon\Config\Adapter\Ini(FOLDER_PROJECT . 'apps/config/config.ini');
        $this->databaseConnectAction();

        $this->createTablesAction();
    }

    /**
     * Cria um arquivo de configuração com os dados para conexão com o banco de dados
     * através de informações recebidas via POST, e utiliza essas informações para se 
     * conectar ao banco.
     * Exemplo de arquivo:
     * 
     * [database]
     * adapter  = Mysql
     * host     = localhost
     * username = root
     * password = 
     * name     = pluton
     */
    public function configFileCreateAction() {
        
        $database_name = $this->request->getPost('database_name');
        $database_user = $this->request->getPost('database_user');
        $database_passwd = $this->request->getPost('database_passwd');
        $database_host = $this->request->getPost('database_host');

        $config_file = fopen(FOLDER_PROJECT . 'apps/config/config.ini', 'w') or die("Unable to open file!");
        $writing_file = "[database]\n";
        $writing_file .= "adapter  = Mysql\n";
        $writing_file .= "host     = {$database_host}\n";
        $writing_file .= "username = {$database_user}\n";
        $writing_file .= "password = {$database_passwd}\n";
        $writing_file .= "name     = {$database_name}\n";

        fwrite($config_file, $writing_file);
        fclose($config_file);

        $this->databaseSettings();
        
    }

    /**
     * Executa conexão com o banco de dados.
     * @param type $config objeto tipo \Phalcon\Config\Adapter\Ini contendo
     * informações do banco de dados.
     */
    public function databaseConnectAction() {


        $db_conn = array(
            "host" => $this->config->database->host,
            "username" => $this->config->database->username,
            "password" => $this->config->database->password,
            "dbname" => $this->config->database->name
        );
        $db_conn["persistent"] = false;
        $this->connection = new \Phalcon\Db\Adapter\Pdo\Mysql($db_conn);
    }

    /**
     * Cria as tabelas necessárias para o funcionamento do sistema
     */
    public function createTablesAction() {
        
        $this->connection->tableExists('layouts') ? $this->tables->createTableLayouts($this->connection) : NULL;
        $this->connection->tableExists('blogs') ? $this->tables->createTableBlogs($this->connection) : NULL ;
        $this->connection->tableExists('users') ? $this->tables->createTableUsers($this->connection) : NULL;
        $this->connection->tableExists('posts') ? $this->tables->createTablePosts($this->connection) : NULL;
    }

    public function newBlogAction() {
        // views/setup/newBlog.phtml
    }

    public function newUserAction() {
        // views/setup/newUser.phtml
    }

}
