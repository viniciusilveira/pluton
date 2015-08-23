<?php
/**
* Class and Function List:
* Function list:
* - registerAutoloaders()
* - registerServices()
* - (()
* - (()
* Classes list:
* - Module
*/
namespace Multiple\Backend;

class Module {

    public function registerAutoloaders() {

        $loader = new \Phalcon\Loader();

        $loader->registerNamespaces(array(
            'Multiple\Backend\Controllers' => '../apps/backend/controllers/',
            'Multiple\Backend\Models' => '../apps/backend/models/',
            'Multiple\Backend\Plugins' => '../apps/backend/plugins/',
            'Multiple\Library' => '../apps/library/'
        ));

        $loader->register();
    }

    /**
     * Registra os serviços específicos para este módulo
     */
    public function registerServices($di) {

        //Registra o dispatcher
        $di->set('dispatcher', function () {

            $dispatcher = new \Phalcon\Mvc\Dispatcher();

            $eventManager = new \Phalcon\Events\Manager();
            $eventManager->attach('dispatch', new \Acl('backend'));

            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace("Multiple\Backend\Controllers\\");
            return $dispatcher;
        });

        //Registra os diretórios das views
        $di->set('view', function () {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('../apps/backend/views/');
            return $view;
        });
    }
}
