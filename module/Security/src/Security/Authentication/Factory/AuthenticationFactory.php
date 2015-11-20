<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 21/08/15
 * Time: 17:08
 */

namespace Security\Authentication\Factory;

use Security\Authentication\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;


class AuthenticationFactory implements  FactoryInterface {


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new AuthenticationService(new Session(), new Adapter($entityManager) );
    }
}