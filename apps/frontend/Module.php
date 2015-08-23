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
namespace Multiple\Frontend;

class Module {

    public function registerAutoloaders() {

        $loader = new \Phalcon\Loader();

        $loader->registerNamespaces(array(
            'Multiple\Frontend\Controllers' => '../apps/frontend/controllers/',
            'Multiple\Frontend\Models' => '../apps/frontend/models/',
            'Multiple\Frontend\Plugins' => '../apps/frontend/plugins/',
            'Multiple\Library' => '../apps/library/'
        ));

        $loader->register();
    }

    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    public function registerServices($di) {

        //Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();

            //Attach a event listener to the dispatcher
            $eventManager = new \Phalcon\Events\Manager();
            $eventManager->attach('dispatch', new \Acl('frontend'));

            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace("Multiple\Frontend\Controllers\\");
            return $dispatcher;
        });

        //Registering the view component
        $di->set('view', function () {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('../apps/frontend/views/');
            return $view;
        });
    }
}
