<?php
return [
    'backend' => [
        'frontName' => 'admin_frontname'
    ],
    'remote_storage' => [
        'driver' => 'file'
    ],
    'cache' => [
        'graphql' => [
            'id_salt' => 'OBv06PKffIcChxfKPafEdvj9uyOc84eL'
        ],
        'frontend' => [
            'default' => [
                'id_prefix' => 'b1d_'
            ],
            'page_cache' => [
                'id_prefix' => 'b1d_'
            ]
        ],
        'allow_parallel_generation' => false
    ],
    'config' => [
        'async' => 0
    ],
    'queue' => [
        'consumers_wait_for_messages' => 1
    ],
    'crypt' => [
        'key' => 'base64HhkDB+aYTE9uXeQMMY+vYlrNRyCWrWRSsoIdDwmiT6k='
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => 'localhost:3308',
                'dbname' => 'magento2a3',
                'username' => 'user1',
                'password' => 'rootpw',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1',
                'driver_options' => [
                    1014 => false
                ]
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'developer',
    'session' => [
        'save' => 'files',
        'save_path' => __DIR__ . '/../../var/session'
    ],
    'lock' => [
        'provider' => 'db'
    ],
    'directories' => [
        'document_root_is_pub' => true
    ],
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'compiled_config' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'graphql_query_resolver_result' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1
    ],
    'downloadable_domains' => [
        'magento2a3'
    ],
    'install' => [
        'date' => 'Fri, 04 Apr 2025 19:49:11 +0000'
    ]
];
