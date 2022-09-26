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
    '_navigation' => [
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
    '_router' => [
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
    'iiifViewersSetting' => [
        "manifest" => "iiif-logo.svg",
        "viewers" => [
            // アイコン設定を追加
            [
                "url" => "http://mirador.cultural.jp/?manifest=",
                "label" => "Mirador",
                "icon" => "mirador3.svg",
            ],
            [
                "url" => "http://universalviewer.io/examples/uv/uv.html#?manifest=",
                "label" => "Universal Viewer",
                "icon" => "uv.jpg",
            ],
            [
                "url" => "http://codh.rois.ac.jp/software/iiif-curation-viewer/demo/?manifest=",
                "label" => "IIIF Curation Viewer",
                "icon" => "icp-logo.svg",
            ]
        ]
    ],
    // 依存モジュール追加
    'dependencies' => [
        'IiifServer',
    ],
];
