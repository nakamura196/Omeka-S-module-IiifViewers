<?php declare(strict_types=1);
namespace IiifViewers;

return [
    // 追加
    'api_adapters' => [
        'invokables' => [
            'iiif_viewers_icons' => Api\Adapter\IiifViewersIconAdapter::class,
        ],
    ],
    // 追加
    'entity_manager' => [
        'is_dev_mode' => true,
        'mapping_classes_paths' => [
            dirname(__DIR__) . '/src/Entity',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'view_helpers' => [
        // 追加
        'invokables' => [
            'formIcon' => Form\View\Helper\FormIcon::class,
            'formIconThumbnail' => Form\View\Helper\FormIconThumbnail::class,
        ],
        'factories' => [
            'IiifViewers' => Service\ViewHelper\IiifViewersFactory::class,
        ],
        // 追加
        'delegators' => [
            'Laminas\Form\View\Helper\FormElement' => [
                Service\Delegator\FormElementDelegatorFactory::class,
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            // 以下不要
            // 'IiifViewers\Controller\Player' => Controller\PlayerController::class,
        ],
        // 追加
        'factories' => [
            'IiifViewers\Controller\Admin\Index' => Service\Controller\Admin\IndexControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            // Form\ConfigForm::class => Form\ConfigForm::class,
        ],
        // factoryに変更
        'factories' => [
            Form\ConfigForm::class => Service\Form\ConfigFormFactory::class,
        ],
    ],
    // 追加
    'navigation' => [
        'AdminModule' => [
            [
                'label' => 'IIIF Viewers',
                'route' => 'admin/iiif-viewers',
                'resource' => 'IiifViewers\Controller\Admin\Index',
                'controller' => 'Index',
                'action' => 'index',
            ],
        ],
    ],
    'router' => [
        'routes' => [
            // 追加
            'admin' => [
                'child_routes' => [
                    'iiif-viewers' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/iiif-viewers',
                            'defaults' => [
                                '__NAMESPACE__' => 'IiifViewers\Controller\Admin',
                                'controller' => 'Index',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'sidebar-select' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/sidebar-select',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'IiifViewers\Controller\Admin',
                                        'controller' => 'Index',
                                        'action' => 'sidebar-select',
                                    ],
                                ],
                            ],
                            'add' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/add',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'IiifViewers\Controller\Admin',
                                        'controller' => 'Index',
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                            'del' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/delete',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'IiifViewers\Controller\Admin',
                                        'controller' => 'Index',
                                        'action' => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],

                ],
            ],
            // 以下の設定は不要
            // 'site' => [
            //     'child_routes' => [
            //         'resource-id-universal-viewer' => [
            //             'type' => \Laminas\Router\Http\Segment::class,
            //             'options' => [
            //                 'route' => '/:resourcename/:id/uv',
            //                 'constraints' => [
            //                     'resourcename' => 'item|item\-set',
            //                     'id' => '\d+',
            //                 ],
            //                 'defaults' => [
            //                     '__NAMESPACE__' => 'IiifViewers\Controller',
            //                     'controller' => 'Player',
            //                     'action' => 'play',
            //                 ],
            //             ],
            //         ],
            //     ],
            // ],
            // 'IiifViewers_player' => [
            //     'type' => 'segment',
            //     'options' => [
            //         'route' => '/:resourcename/:id/universal-viewer',
            //         'constraints' => [
            //             'resourcename' => 'item|item\-set',
            //             'id' => '\d+',
            //         ],
            //         'defaults' => [
            //             '__NAMESPACE__' => 'IiifViewers\Controller',
            //             'controller' => 'Player',
            //             'action' => 'play',
            //         ],
            //     ],
            // ],
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
    'iiifviewers' => [
        // アイコン設定を追加
        'config' => [
            'url' => [
                'iiifviewers_mirador' => 'http://mirador.cultural.jp/?manifest=',
                'iiifviewers_universal_viewer' => 'http://universalviewer.io/examples/uv/uv.html#?manifest=',
                'iiifviewers_curation_viewer' => 'http://codh.rois.ac.jp/software/iiif-curation-viewer/demo/?manifest=',
                'iiifviewers_tify' => 'http://tify.sub.uni-goettingen.de/demo.html?manifest=',
                ],
            'icons' => [
                'iiifviewers_mirador_icon' => 'mirador3.svg',
                'iiifviewers_universal_viewer_icon' => 'uv.jpg',
                'iiifviewers_curation_viewer_icon' => 'icp-logo.svg',
                'iiifviewers_tify_icon' => 'tify-logo.svg',
                'logo' => 'iiif-logo.svg',
                ],
        ],
    ],
    // 依存モジュール追加
    'dependencies' => [
        'IiifServer',
    ],
];
