<?php declare(strict_types=1);
namespace IiifViewers;

return [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'IiifViewers' => Service\ViewHelper\IiifViewersFactory::class,
        ],
    ],
    'controllers' => [
        'invokables' => [
            'IiifViewers\Controller\Player' => Controller\PlayerController::class,
        ],
    ],

    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'resource-id-universal-viewer' => [
                        'type' => \Laminas\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/:resourcename/:id/uv',
                            'constraints' => [
                                'resourcename' => 'item|item\-set',
                                'id' => '\d+',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'IiifViewers\Controller',
                                'controller' => 'Player',
                                'action' => 'play',
                            ],
                        ],
                    ],
                ],
            ],
            'IiifViewers_player' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/:resourcename/:id/universal-viewer',
                    'constraints' => [
                        'resourcename' => 'item|item\-set',
                        'id' => '\d+',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'IiifViewers\Controller',
                        'controller' => 'Player',
                        'action' => 'play',
                    ],
                ],
            ],
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\ConfigForm::class => Form\ConfigForm::class,
        ],
    ],
    'iiifviewers' => [
        'config' => [
            'iiifviewers' => [
                'iiifviewers_mirador' => 'http://da.dl.itc.u-tokyo.ac.jp/mirador/?manifest=',
                'iiifviewers_universal_viewer' => 'http://universalviewer.io/examples/uv/uv.html#?manifest=',
                'iiifviewers_curation_viewer' => 'http://codh.rois.ac.jp/software/iiif-curation-viewer/demo/?manifest==',
                'iiifviewers_tify' => 'http://tify.sub.uni-goettingen.de/demo.html?manifest=',
                ],
            'iiifviewers_mirador' => 'http://da.dl.itc.u-tokyo.ac.jp/mirador/?manifest=',
            'iiifviewers_tify' => 'http://universalviewer.io/examples/uv/uv.html#?manifest=',
        ],
    ],
];
