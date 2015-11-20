<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 28/08/15
 * Time: 15:28
 */

namespace Security\Service;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ACL extends  SecurityAbstractService {

    private  $acl = null;

    private $recursosDesprotegidos = array();

    public function getRolesACL( \Zend\Permissions\Acl\Acl $acl, \Doctrine\ORM\EntityManager $em)
    {

        $repo = $em->getRepository('Security\Entity\Grupo');
        foreach($repo->fetchPairs() as $grupo ){
            $acl->addRole($grupo);
        }
        return $acl;
    }

    public function getResourcesACL( \Zend\Permissions\Acl\Acl $acl, \Doctrine\ORM\EntityManager $em)
    {
        $repo = $em->getRepository('Security\Entity\RecursoSistema');
        foreach($repo->fetchPairs() as $recurso ){
            $acl->addResource($recurso);
        }
        // carrega os recurso desprotegidos
        foreach($this->getRecursosDesprotegidos() as $recurso){
            if( !$acl->hasResource($recurso)) $acl->addResource($recurso);
        }
        return $acl;
    }

    public function getPermissionsACL( \Zend\Permissions\Acl\Acl $acl, \Doctrine\ORM\EntityManager $em)
    {
        $repoPermissao = $em->getRepository('Security\Entity\PermissaoAcl');
        $permissions = $repoPermissao->getPermissions();
        foreach($permissions as $permission){
            if($permission->getPermissao() == 'allow'){
                $acl->allow($permission->getGrupo()->getNome(), $permission->getRecursoSistema()->getUrl());
            } else{
                $acl->deny($permission->getGrupo()->getNome(), $permission->getRecursoSistema()->getUrl());
            }
        }
        return $acl;
    }
    public function carregarPermissionsACLSistema( $em)
    {
        $acl = new \Zend\Permissions\Acl\Acl();
        //$em = $sm->get('Doctrine\ORM\EntityManager');
        //pegar as resources do repositorio
        $acl = $this->getRolesACL($acl, $em);
        //pegar as roles do repositorio
        $acl = $this->getResourcesACL($acl, $em);
        //Pegar as permissoes do repositorio
        $acl = $this->getPermissionsACL($acl, $em);
        //Pegar as permissoes de recursos desprotegidos
        $acl = $this->getPermissosAclRecursoDesprotegidos($acl, $em);
        return $acl;
    }

    public function getPermissosAclRecursoDesprotegidos( \Zend\Permissions\Acl\Acl $acl, \Doctrine\ORM\EntityManager $em)
    {
        $repo = $em->getRepository('Security\Entity\Grupo');
        foreach($repo->fetchPairs() as $grupo ){
           foreach($this->getRecursosDesprotegidos() as $recurso){
               $acl->allow($grupo, $recurso);
           }
        }
        return $acl;
    }

    public function getRouteResource($paramsRequest)
    {
        $resourceName = $paramsRequest['controller'] . 'Controller\\'. $paramsRequest['action'];
        //fazer processo inverso pra converter partes de uma url amigavel, no padrão camelCase
        return  preg_replace_callback('~-([a-z])~', function ($match) use($resourceName) {
            return strtoupper($match[1]);
        }, $resourceName);
    }

    public function checarPermissoesAcl(  $paramsRequest = array())
    {
        $sm = $this->getServiceLocator();
        $repoRecursoSistema = $this->getEm('Security\Entity\RecursoSistema');
        $this->setRecursosDesprotegidos($repoRecursoSistema->getRecursosDesprotegidosAcl());
        /**
        * @var $auth \Zend\Authentication\AuthenticationService
        */
        $auth = $sm->get('Zend\Authentication\AuthenticationService');
        $grupo = $auth->getIdentity()["grupo"];
        $resourceName = $this->getRouteResource($paramsRequest);

        $acl = $this->carregaAcl();

        //checar se o recurso não existe
        if( !$acl->hasResource($resourceName)){
            return false;
        }

       if($acl->isAllowed($grupo, $resourceName)== false)
        {
            return false;
        }
        return true;
    }

    public function carregaAcl()
    {
        $em = $this->getEm();
        $cache = $this->getServiceLocator()->get('Cache');
        $acl = $cache->getItem('cacheApp1');
        if(is_null($acl)){
            $acl = $this->carregarPermissionsACLSistema($em);
            $cache->addItem('cacheApp1', $acl);
        }
        return $acl;
    }

    /**
     * @param array $recursosDesprotegidos
     */
    public function setRecursosDesprotegidos($recursosDesprotegidos)
    {
        $this->recursosDesprotegidos = $recursosDesprotegidos;
    }

    /**
     * @return array
     */
    public function getRecursosDesprotegidos()
    {
        return $this->recursosDesprotegidos;
    }


} 
