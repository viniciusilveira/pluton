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

    private $users;
    private $blogs;

    /**
     * Construct necessário para objetos
     */
    public function __construct() {

        $this->users = new \Multiple\Backend\Models\Users;
        $this->blogs = new \Multiple\Backend\Models\Blogs;
        $di = $this->getDI();
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
        echo FOLDER_PROJECT;
        if (file_exists(FOLDER_PROJECT . 'apps/config/config.ini')) {
            if (!$this->verifyUserExistAction()) {
                return 'user';
            } elseif (!$this->verifyBlogExistAction()) {
                return 'blog';
            } else {
                return 'ok';
            }
        } else {
            return 'file';
        }
    }

    /**
     * Verifica se existe um usuário super-administrador criado no banco de dados
     * @return boolean true caso exista, e falso caso não exista
     */
    public function verifyUserExistAction() {
        $users = new \Multiple\Backend\Models\Users();
        $qtd_users = $users->count();
        if ($qtd_users > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica se existe blog criado no banco de dados
     * @return boolean true caso exista, e falso caso não exista
     */
    public function verifyBlogExistAction() {
        $blogs = new \Multiple\Backend\Models\Blogs();
        $blog_exists = $blogs->count();
        if ($blog_exists > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function databaseConfigAction() {
        // views/setup/databaseConfig.phtml
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
        
        $writing_file  = "[database]\n";
        $writing_file .= "adapter  = Mysql\n";
        $writing_file .= "host     = {$database_host}\n";
        $writing_file .= "username = {$database_user}\n";
        $writing_file .= "password = {$database_passwd}\n";
        $writing_file .= "name     = {$database_name}\n";
        
        fwrite($config_file, $writing_file);
        fclose($config_file);

        $config = new \Phalcon\Config\Adapter\Ini(FOLDER_PROJECT . 'apps/config/config.ini');
        $this->databaseConnectAction($config);
        
        $this->TablesCreateAction();
    }
    
    /**
     * Executa conexão com o banco de dados.
     * @param type $config objeto tipo \Phalcon\Config\Adapter\Ini contendo
     * informações do banco de dados.
     */
    public function databaseConnectAction($config){
        //Seta a conexão com o banco de dados
        $this->di->set('db', function() use ($config) {
            $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
            return new $dbclass(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->name
            ));
        });
    }
    
    /**
     * Cria as tabelas necessárias para o funcionamento do sistema
     */
    public function TablesCreateAction(){
        
    }

    public function newBlogAction() {
        // views/setup/newBlog.phtml
    }
    
    public function newUserAction(){
        // views/setup/newUser.phtml
    }

}
