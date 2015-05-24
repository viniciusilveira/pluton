<?php

error_reporting(E_ALL);

(new \Phalcon\Debug)->listen();
/**
 * Define uma URL padrão para acesso ao projeto
 */
define('URL_PROJECT', 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/pluton/');

/**
 * Define uma variável contendo o diretório raiz do projeto
 */
define('FOLDER_PROJECT', __DIR__ . '/../');



class Application extends \Phalcon\Mvc\Application {

    protected function _registerServices() {

        $di = new \Phalcon\DI\FactoryDefault();
        
        $loader = new \Phalcon\Loader();
        $loader->registerDirs(
                array(
                    FOLDER_PROJECT . '/apps/library/'
                )
        )->register();

        //usando autoloader do composer para carregar as classes do vendor
        require_once FOLDER_PROJECT . 'vendor/autoload.php';

        $di->set('router', function() {

            $router = new \Phalcon\Mvc\Router();

            $router->setDefaultModule("frontend");

            $router->add('/:controller/:action', array(
                'module' => 'frontend',
                'controller' => 1,
                'action' => 2,
            ));

            $router->add('/:controller/:action', array(
                'module' => 'backend',
                'controller' => 1,
                'action' => 2,
            ));

            $router->add("/settings", array(
                'module' => 'backend',
                'controller' => 'index',
                'action' => 'index',
            ));
            /**
             * @todo: Verificar se esse router está ocasionando conflito ao carregar Actions
             */
            $router->add("/login", array(
                'module' => 'backend',
                'controller' => 'login',
                'action' => 'index',
            ));

            return $router;
        });
        /**
         * Caso exista o arquivo de configuração config.ini coleta os dados existentes nele e 
         * conecta com o banco de dados
         */
        if (file_exists('../apps/config/config.ini')) {
            $config = new \Phalcon\Config\Adapter\Ini('../apps/config/config.ini');
            
            //Seta a conexão com o banco de dados
            $di->set('db', function() use ($config) {
                $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
                return new $dbclass(array(
                    "host" => $config->database->host,
                    "username" => $config->database->username,
                    "password" => $config->database->password,
                    "dbname" => $config->database->name
                ));
            });
        } 

        $this->setDI($di);
    }

    public function main() {

        $this->_registerServices();

        //Registra os módulos existentes
        $this->registerModules(array(
            'frontend' => array(
                'className' => 'Multiple\Frontend\Module',
                'path' => '../apps/frontend/Module.php'
            ),
            'backend' => array(
                'className' => 'Multiple\Backend\Module',
                'path' => '../apps/backend/Module.php'
            )
        ));

        echo $this->handle()->getContent();
    }

}

$application = new Application();
$application->main();
