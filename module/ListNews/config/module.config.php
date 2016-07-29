<?php
namespace ListNews;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'listnews' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/listnews[/:action[/:num][/page:page]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'num'     => '[0-9]+',
                        'page'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ListNewsController::class,
                        'action'     => 'index',
                        'page'     => 1,
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'listnews' => __DIR__ . '/../view',
        ],
    ],
];
