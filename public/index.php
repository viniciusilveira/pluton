<?php
/**
 * Class and Function List:
 * Function list:
 * - _registerServices()
 * - (()
 * - (()
 * - (()
 * - main()
 * Classes list:
 * - Application extends \
 */
define('DEBUG', true);

if (DEBUG) {
    error_reporting(E_ALL);

    $debug = new \Phalcon\Debug();
    $debug->listen();
}

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
        $loader->registerDirs(array(
            FOLDER_PROJECT . '/apps/library/',
            FOLDER_PROJECT . '/apps/backend/models',
            FOLDER_PROJECT . '/apps/frontend/models'
        ))->register();

        //usando autoloader do composer para carregar as depêndencias instaladas via composer
        require_once FOLDER_PROJECT . 'vendor/autoload.php';

        $di->set('router', function () {

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

            $router->add("/admin", array(
                'module' => 'backend',
                'controller' => 'index',
                'action' => 'index',
            ));

            $router->add("/editor", array(
                'module' => 'frontend',
                'controller' => 'index',
                'action' => 'index',
            ));

            $router->add("/index/:action", array(
                'controller' => 'index',
                'action' => 1,
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
            $di->set('db', function () use ($config) {
                $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
                return new $dbclass(array(
                    "host" => $config->database->host,
                    "username" => $config->database->username,
                    "password" => $config->database->password,
                    "dbname" => $config->database->name,
                    "charset" => 'utf8',
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
            ) ,
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
