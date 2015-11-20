<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Security;
use Zend\Cache\StorageFactory;

return array(
    'router' => array(
        'routes' => array(

            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'acesso-negado' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/acesso-negado',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Security\Controller',
                        'controller'    => 'Banido',
                        'action'        => 'acessoNegado',
                    ),
                )
            ),
            'security' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/security',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Security\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/:id]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'paginator' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/page/:page]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '\d+'
                            ),
                            'defaults' => array(
                                'action' => 'index',
                                'page' => 1
                            ),
                        )
                    )
                ),

            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'Cache' => function ($sm) {
                    // incluindo o arquivo config para pegar o cache adapter
                    $config = include __DIR__ . '/../../../config/application.config.php';
                    $cache = StorageFactory::factory(array(
                        'adapter' => array(
                            'name' => $config['cache']['adapter'],
                            'options' => array(
                                // tempo de validade do cache
                                'ttl' => 7200,
                                // adicionando o diretorio data/cache para salvar os caches.
                                'cacheDir' => __DIR__ . '/../../../data/cache'
                            ),
                        ),
                        'plugins' => array(
                            'exception_handler' => array('throw_exceptions' => false),
                            'Serializer'
                        )
                    ));

                    return $cache;
                },
            'Zend\Authentication\AuthenticationService' => 'Security\Authentication\Factory\AuthenticationFactory'
        ),
        'invokables' => array(
            //Serviços do modulo
            'security-service-grupo' => 'Security\Service\Grupo',
            'sercurity-service-permissao' => 'Security\Service\PermissaoACL',
            'security-service-acl' => 'Security\Service\ACL',
            'security-service-usuario' => 'Security\Service\Usuario',
            'security-service-funcionario' => 'Security\Service\Funcionario'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Security\Controller\Index' => 'Security\Controller\IndexController',
            'Security\Controller\Permissao' => 'Security\Controller\PermissaoController',
            'Security\Controller\Autenticacao' => 'Security\Controller\AutenticacaoController',
            'Security\Controller\Funcionario' => 'Security\Controller\FuncionarioController',
            'Security\Controller\Grupo' => 'Security\Controller\GrupoController',
            'Security\Controller\Banido' => 'Security\Controller\BanidoController',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__.'/../src/'. __NAMESPACE__. '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )

        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'security/index/index' => __DIR__ . '/../view/security/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        ),
    ),
    'view_helpers' => array(
        'invokables'=> array(
            'rotaRecursoSistema' => 'Security\View\Helper\RotaRecursoSistema'
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'MyAclPlugin' => 'Security\Controller\Plugin\MyAclPlugin',
        )
    ),
);
