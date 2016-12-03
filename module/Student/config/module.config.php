<?php
return array(
    'router' => array(
        'routes' => array(
            'student' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/student/student[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Student\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'stexam' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/stexam[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Student\Controller\Exam',
                        'action' => 'index',
                    ),
                ),
            ),
            'stevent' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/stevent[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Student\Controller\Event',
                        'action' => 'index',
                    ),
                ),
            ),
            'streferral' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/streferral[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Student\Controller\Referral',
                        'action' => 'referral-dashboard',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Student\Controller\Index' => 'Student\Controller\IndexController',
            'Student\Controller\Exam' => 'Student\Controller\ExamController',
            'Student\Controller\Event' => 'Student\Controller\EventController',
            'Student\Controller\Referral' => 'Student\Controller\ReferralController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Student' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'student_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Student/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                     'Student\Entity' =>  'student_driver'
                ),
            ),
        ),
    ),      
);
