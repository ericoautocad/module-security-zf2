<?php
namespace Security;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sharedEvents = $eventManager->getSharedManager();
        $application = $e->getApplication();
        $sm = $application->getServiceManager();

        /*SETANDO BASE URL */
        $e->getRequest()->setBaseUrl('/');
        $basePathHelper = $sm->get('viewRenderer')->plugin('basePath');
        /* @var $basePathHelper \Zend\View\Helper\BasePath */
        $basePathHelper->setBasePath('/');
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($ev) use ($sm) {

            $auth = $ev->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');

            if($auth->hasIdentity() ){
                return;
            }
            if($ev->getRouteMatch()->getParam('action') == 'login'){
                return;
            }
            $target = $ev->getTarget();
            $flash = $sm->get('ControllerPluginManager')->get('FlashMessenger')->addErrorMessage('Efetue login no sistema para acessar este recurso!');
            return $target->redirect()->toUrl('/security/autenticacao/login');
            //return;
        }, 3);
        $eventManager->attach('route', array($this, 'loadConfiguration'), 2);
    }

    public function loadConfiguration(MvcEvent $e)
    {
        $application   = $e->getApplication();
        $sm            = $application->getServiceManager();

        $sharedManager = $application->getEventManager()->getSharedManager();
            $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
                function($ev) use ($sm) {

                   $authorizationService =  $sm->get('security-service-acl');
                   $auth = $ev->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');

                    if($auth->hasIdentity()){

                        $is_authorized = $authorizationService->checarPermissoesAcl( $ev->getRouteMatch()->getParams()  );
                        if(!$is_authorized ){
                            $target = $ev->getTarget();
                            return $target->redirect()->toUrl('/acesso-negado');
                           // echo "Voce não tem permissao para acessar este recurso";
                           die;
                        }
                    }
                }
            );

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    /*public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
        );
    }*/

    /*public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }*/
    public function getAutoloaderConfig()
    {
        return array(
           /* 'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),*/
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }


    /*public function getViewHelperConfig()
    {
        return array(
            'invokables' => array (
                'rotaRecursoSistema' => new \Security\View\Helper\RotaRecursoSistema(),
            ),
        );
    }*/
}
