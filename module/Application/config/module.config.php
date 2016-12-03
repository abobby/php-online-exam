<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                    	'__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'dashboard' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/dashboard',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'dashboard',
                    ),
                ),                
            ),
            'prize' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/prize',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'prize',
                    ),
                ),                
            ),
            'winners' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/winners',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'winners',
                    ),
                ),                
            ),
            'about-us' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/about-us',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'about-us',
                    ),
                ),                
            ),
            'support' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/support',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'support',
                    ),
                ),                
            ),
            'contact' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/contact',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'contact',
                    ),
                ),                
            ),
            'terms' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/terms',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'terms',
                    ),
                ),                
            ),
            'faqs' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/faqs',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'faqs',
                    ),
                ),                
            ),
            'reset-password' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/reset-password',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'reset-password',
                    ),
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
        ),
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
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/org-layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/layout'           => __DIR__ . '/../view/layout/app_layout.phtml',
            'layout/home-layout'           => __DIR__ . '/../view/layout/home_layout.phtml',
            'layout/app-header'           => __DIR__ . '/../view/layout/app_header.phtml',
            'layout/app-footer'           => __DIR__ . '/../view/layout/app_footer.phtml',
            'layout/top-menu-student'           => __DIR__ . '/../view/layout/top_menu_student.phtml',
            'layout/top-menu-manage'           => __DIR__ . '/../view/layout/top_menu_manage.phtml',
            'layout/top-menu-admin'           => __DIR__ . '/../view/layout/top_menu_admin.phtml',
            'layout/side-menu-student'           => __DIR__ . '/../view/layout/side_menu_student.phtml',
            'layout/side-menu-manage'           => __DIR__ . '/../view/layout/side_menu_manage.phtml',
            'layout/side-menu-admin'           => __DIR__ . '/../view/layout/side_menu_admin.phtml',
            'layout/blank'           => __DIR__ . '/../view/layout/blank_layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),
            'orm_default' => array(
                    'drivers' => array(
                            'Application\Entity' => 'application_entities'
                    )
            ),
            'cache' => array(
                'class' => 'Doctrine\Common\Cache\ApcCache'
            ),
            'configuration' => array(
                'orm_default' => array(
                    'metadata_cache' => 'apc',
                    'query_cache'    => 'apc',
                    'result_cache'   => 'apc'
                )
            ),
    )),
 
    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'Application\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),
 
    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
 
        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entity_manager.orm_default',
                'role_entity_class' => 'Application\Entity\Role',
            ),
        ),
    ),
);
