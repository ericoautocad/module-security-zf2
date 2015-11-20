<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 01/09/15
 * Time: 14:03
 */

namespace Security\Cache;


use Doctrine\Common\Cache\ApcCache;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApcFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $apcCache = new ApcCache();

        return $apcCache;
    }
} 