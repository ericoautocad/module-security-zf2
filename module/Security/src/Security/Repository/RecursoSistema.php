<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 27/08/15
 * Time: 10:31
 */

namespace Security\Repository;


use Zend\ServiceManager\ServiceLocatorInterface;

class RecursoSistema extends  SecurityAbstractRepository {

    private $modulesSystem = array();

    /**
     * seta a propriedade dos modulos do sistema
     * @param array $modulesSystem
     */
    public function setModulesSystem( ServiceLocatorInterface $sm)
    {
        $moduleManager = $sm->get('ModuleManager');
        // obtem a propriedade que contem os modulos do sistema
        $modules = $moduleManager->getModules();
        foreach($modules as $mod){
            if( is_dir( './module/'.$mod ) ){
                $this->modulesSystem[$mod] = $mod;
            }
        }
        return $this->modulesSystem;
    }

    /**
     * Obtem os modulos do sistema
     * @return array
     */
    public function getModulesSystem()
    {
        return $this->modulesSystem;
    }

    /**Carrega automaticamente todas as actions, controllers e modulos do sistema
     * @param ServiceLocatorInterface $sm
     * @return mixed|string
     */
    public function loadResourcesSystem( ServiceLocatorInterface $sm)
    {
        $this->setModulesSystem($sm);
        $resourcesMap =  $this->loadResourceControllerModule(  "Zend\Mvc\Controller\AbstractActionController" );

        return $resourcesMap;
    }

    /**
     * Carrega os recursos controllers dos modulos da aplicacao.
     * @param $pathController
     * @param $module
     * @param string $parentClass - indica qual é a classe parent da classe, para saber se é um controller
     * @return mixed|string
     */
    public function loadResourceControllerModule( $parentClass = "Zend\Mvc\Controller\AbstractActionController" )
    {
        $resourcesController = array();
       foreach($this->getModulesSystem() as $module){
            $pathDefaultControllersModule = './module/'.$module.'/src/'.$module.'/Controller/';

            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pathDefaultControllersModule)) as $filename)
            {
                if ($filename->isDir()) continue;
                //criar um metodo para acomodar este tratamento
                $pathController = str_replace('./module/'.$module.'/src/', '', $filename);
                $pathController = str_replace('.php', '', $pathController);
                $pathController =  "\\".str_replace('/', "\\", $pathController);
                if($pathController != "\\".$module."\\Controller\\AbstractController"){
                    $oReflectionClass = new \ReflectionClass($pathController);

                    if( is_subclass_of (  $oReflectionClass->getName() , $parentClass ) ||
                        is_subclass_of (  $oReflectionClass->getName() , "Base\Controller\AbstractController" )){
                        $actions = $this->loadResourceActionsController($oReflectionClass);
                        if(!empty($actions)){
                            $resourcesController[$module][ $oReflectionClass->getName() ] = $actions;
                        }
                    }
                }

            }
       }
        return $resourcesController;
    }

    /**
     * Carrega os recursos, actions de um controller especifico.
     * @param \ReflectionClass $oReflectionClass
     * @return array
     */
    public function loadResourceActionsController( \ReflectionClass $oReflectionClass)
    {
        $methods = $oReflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        $actionsController = array();
        if(!empty($methods)){
            foreach($methods as $method){
                if( strstr ( $method->getName(), "Action" ) !== false  &&
                    ! in_array( $method->getName(), array( 'getMethodFromAction', 'notFoundAction')) &&
                    $method->class != "Zend\Mvc\Controller\AbstractActionController" &&
                    $this->checaResourceActionsControllerNaoEstaDesprotegidaAcl($method->class, $method->getName())
                ){
                    $actionsController[] = str_replace("Action", "", $method->getName()) ;
                }

            }
        }
        return $actionsController;
    }

    /**
     * metodo que retorna recusos livres para todas as rules da acl
     * @return array
     */
    public function getRecursosDesprotegidosAcl()
    {
        return array(
            'Admin\Controller\IndexController\index',
            'Application\Controller\IndexController\index',
            'Security\Controller\AutenticacaoController\index',
            'Security\Controller\AutenticacaoController\login',
            'Security\Controller\AutenticacaoController\logout',
            'Security\Controller\BanidoController\acessoNegado',
            //'Security\Controller\AutenticacaoController\alterarDadosAcesso'
        );
    }

    public function checaResourceActionsControllerNaoEstaDesprotegidaAcl($classeController, $metodoAction)
    {
        $actionDisprotegidaAcl = $classeController."\\".str_replace("Action", "", $metodoAction);
       return !in_array($actionDisprotegidaAcl, $this->getRecursosDesprotegidosAcl())? true : false;
    }

    /**
     * tem função similar ao fetchPairs da classe mãe, porem com essa tabela não tem os campos id e nome, fez necessario
     * a implementação deste método personalizado.
     * @return array
     */
    public function fetchPairs()
    {
        $result = $this->findAll();

        $arrResult = array();
        if($result){
            foreach($result as $item){
                $arrResult[$item->getId()] = $item->getUrl();
            }
        }

        return $arrResult;
    }

} 