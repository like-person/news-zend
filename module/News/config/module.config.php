<?php
namespace News;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'news' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/news[/:action[/:news_id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'news_id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\NewsController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'news' => __DIR__ . '/../view',
        ],
    ],
];

