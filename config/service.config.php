<?php
return array(

        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'UserService' =>  function($sm)
                {
                    echo "testando auto load desnecessário";
                    $em = $sm->get('Doctrine\ORM\EntityManager');
                    return new \Application\Service\UserService($sm, $em);
                }
        ),
        'invokables' => array(

        )

);