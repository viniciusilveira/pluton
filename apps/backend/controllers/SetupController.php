<?php
namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Users, \Phalcon\Crypt AS Crypt;

/**
 * Classe para conexão e configuração dos dados necessários para inicialização
 * do blog
 *
 * OBSERVAÇÃO: Necessário extender a classe Injectable ao invés da Controllers para
 * ser possivel sobescrever o método __construct
 */
class SetupController extends \Phalcon\DI\Injectable
{
    
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
            if (!$this->user->verifyUsersExistAction()) {
                return 'user';
            } 
            elseif (!$this->blog->verifyBlogExistAction()) {
                return 'blog';
            } 
            else {
                return 'ok';
            }
        } 
        else {
            return 'file';
        }
    }
    
    /**
     * Carrega a view para inserção dos dados de conexão com o banco de dados
     */
    public function databaseConfigAction() {
        
        // views/setup/databaseConfig.phtml
        
        
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
        $config_file = fopen(FOLDER_PROJECT . 'apps/config/config.ini', 'w') or die("Unable to open file!");
        $writing_file = "[database]\n";
        $writing_file.= "adapter  = Mysql\n";
        $writing_file.= "host     = {$database_host}\n";
        $writing_file.= "username = {$database_user}\n";
        $writing_file.= "password = {$database_passwd}\n";
        $writing_file.= "name     = {$database_name}\n";
        
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
        try {
            $this->connection = new \Phalcon\Db\Adapter\Pdo\Mysql($db_conn);
            $this->createTablesAction();
            $data['connection'] = true;
            $data['message'] = 'Banco de dados conectado e configurado!';
            echo json_encode($data);
        }
        catch(\PDOException $e) {
            unlink(FOLDER_PROJECT . 'apps/config/config.ini');
            $data['connection'] = false;
            $data['message'] = 'Erro ao conectar ao banco de dados! Por favor verifique se os dados informados
                                estão corretos e tente novamente!';
            echo json_encode($data);
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
     * Cria um usuário para utilização do sistema
     */
    public function createNewUserAction() {

        //Informa que a action não possui nenhuma view para exibição
        $this->view->disable();
        
        $crypt = new Crypt();
        
        $user_name = $this->request->getPost('user_name');
        $user_email = $this->request->getPost('user_email');
        $user_login = $this->request->getPost('user_login');
        $user_passwd = $crypt->encrypt('p1u70ncm5', $this->request->getPost('user_passwd'));
        $user_type = !empty($this->request->getPost('user_type')) ? $this->request->getPost('user_type') : 'SA';
        
        //Verifica se já existe um usuário no banco de dados; Caso exista retorna uma menssagem informando.
        //Se não cria o usuário e retorna uma menssagem informando.
        if (empty(Users::findFirst())) {
            
            try {
                $data['success'] = $this->user->createUser($user_name, $user_email, $user_login, $user_passwd, $user_type);
                $data['message'] = $data['success'] ? 'Usuário criado com sucesso!' : 'Ocorreu um erro ao criar o usuário. Por favor tente novamente';
                echo json_encode($data);
            }
            catch(\PDOException $e) {
                $data['success'] = false;
                $data['message'] = "Ocorreu um erro: " . $e;
                echo json_encode($data);
            }
        } 
        else {
            $data['success'] = false;
            $data['message'] = 'Já existe um usuário no banco de dados! Por favor verifique os dados informados e tente novamente!';
            echo json_encode($data);
        }
    }
    
    /**
     * Action para upload de imagens para o servidor (Ainda não utilizado - Talvez seja movido para outra classe)
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
            $ext = $file->getExtension();
            $name_img = $file->getName();
            if (!file_exists(FOLDER_PROJECT . 'public/img/users')) mkdir(FOLDER_PROJECT . 'public/img/users');
            $path_img = FOLDER_PROJECT . 'public/img/users/' . $user_login . $file->getExteionsion();
            $file->moveTo($path_img);
            return $name_img;
        } 
        else {
            return $error;
        }
    }
}
