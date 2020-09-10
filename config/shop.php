<?php

return [

    'apc_enabled' => false, // enable for maximum performance if APCu is availalbe
    'apc_prefix' => 'laravel:', // prefix for caching config and translation in APCu
    'pcntl_max' => 4, // maximum number of parallel command line processes when starting jobs

    'routes' => [
        // Docs: https://aimeos.org/docs/Laravel/Custom_routes
        // Multi-sites: https://aimeos.org/docs/Laravel/Configure_multiple_shops
        // 'admin' => ['prefix' => 'admin', 'middleware' => ['web']],
        // 'jqadm' => ['prefix' => 'admin/{site}/jqadm', 'middleware' => ['web', 'auth']],
        // 'jsonadm' => ['prefix' => 'admin/{site}/jsonadm', 'middleware' => ['web', 'auth']],
        'jsonapi' => ['prefix' => '{site}/jsonapi', 'middleware' => ['web']],
        'account' => ['prefix' => '{site}/myaccount', 'middleware' => ['web', 'auth']],
        'default' => ['prefix' => '{site}/shop', 'middleware' => ['web']],
        // 'update' => [],
    ],

    /*
    'page' => [
        // Docs: https://aimeos.org/docs/Laravel/Adapt_pages
        // Hint: catalog/filter is also available as single 'catalog/tree', 'catalog/search', 'catalog/attribute'
        'account-index' => [ 'account/profile','account/subscription','account/history','account/favorite','account/watch','basket/mini','catalog/session' ],
        'basket-index' => [ 'basket/bulk', 'basket/standard','basket/related' ],
        'catalog-count' => [ 'catalog/count' ],
        'catalog-detail' => [ 'basket/mini','catalog/stage','catalog/detail','catalog/session' ],
        'catalog-list' => [ 'basket/mini','catalog/filter','catalog/lists' ],
        'catalog-stock' => [ 'catalog/stock' ],
        'catalog-suggest' => [ 'catalog/suggest' ],
        'catalog-tree' => [ 'basket/mini','catalog/filter','catalog/stage','catalog/lists' ],
        'checkout-confirm' => [ 'checkout/confirm' ],
        'checkout-index' => [ 'checkout/standard' ],
        'checkout-update' => [ 'checkout/update' ],
    ],
    */

    /*
    'resource' => [
        'db' => [
            'adapter' => config('database.connections.mysql.driver', 'mysql'),
            'host' => config('database.connections.mysql.host', '127.0.0.1'),
            'port' => config('database.connections.mysql.port', '3306'),
            'socket' => config('database.connections.mysql.unix_socket', ''),
            'database' => config('database.connections.mysql.database', 'forge'),
            'username' => config('database.connections.mysql.username', 'forge'),
            'password' => config('database.connections.mysql.password', ''),
            'stmt' => ["SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8mb4'; SET SESSION sql_mode='ANSI'"],
            'limit' => 3, // maximum number of concurrent database connections
            'defaultTableOptions' => [
                    'charset' => config('database.connections.mysql.charset'),
                    'collate' => config('database.connections.mysql.collation'),
            ],
        ],
    ],
    */

    'admin' => [
        'jqadm' => [
            'order' => [
                'name' => 'EbdaaStandard',
                'invoice' => [
                    'name' => 'EbdaaInvoiceStandard'
                ]
            ],
            'resource' => [
                'site' =>  [
                    'groups' => ['super'],
                ],
                'subscription' => [
                    'groups' => ['super'],
                ],
                'customer' => [
                    'groups' => ['super'],
                ],
                'product' => [
                    'groups' => ['admin', 'super'],
                ],
                'catalog' => [
                    'groups' => ['admin', 'super'],
                ],
                'attribute' => [
                    'groups' => ['super'],
                ],
                'coupon' => [
                    'groups' => ['super'],
                ],
                'supplier' => [
                    'groups' => ['super'],
                ],
                'service' => [
                    'groups' => ['super'],
                ],
                'plugin' => [
                    'groups' => ['super'],
                ],
                'group' => [
                    'groups' => ['super'],
                ],
                'locale' => [
                    'groups' => ['super'],
                    'site' => [
                        'groups' => ['super'],
                    ],
                ],
                'type' => [
                    'groups' => ['super'],
                    'attribute' => [
                        'groups' => ['super'],
                        'lists' => [
                            'groups' => ['super'],
                        ],
                        'property' => [
                            'groups' => ['super'],
                        ]
                    ],
                    'catalog' => [
                        'lists' => [
                            'groups' => ['super'],
                        ]
                    ],
                    'customer' => [
                        'lists' => [
                            'groups' => ['super'],
                        ],
                        'property' => [
                            'groups' => ['super'],
                        ]
                    ],
                    'media' => [
                        'groups' => ['super'],
                        'lists' => [
                            'groups' => ['super'],
                        ],
                        'property' => [
                            'groups' => ['super'],
                        ]
                    ],
                    'price' => [
                        'groups' => ['super'],
                        'lists' => [
                            'groups' => ['super'],
                        ],
                        'property' => [
                            'groups' => ['super'],
                        ]
                    ],
                    'product' => [
                        'groups' => ['super'],
                        'lists' => [
                            'groups' => ['super'],
                        ],
                        'property' => [
                            'groups' => ['super'],
                        ]
                    ],
                    'service' => [
                        'groups' => ['super'],
                        'lists' => [
                            'groups' => ['super'],
                        ],
                    ],
                    'stock' => [
                        'groups' => ['super'],
                    ],
                    'tag' => [
                        'groups' => ['super'],
                    ],
                    'text' => [
                        'groups' => ['super'],
                        'lists' => [
                            'groups' => ['super'],
                        ],
                    ]
                ],
                'log' => [
                    'groups' => ['super'],
                ],
                'language' => [
                    'groups' => ['super'],
                ],
            ],
            'dashboard' => [
                'order' => [
                    'countpaystatus' => [
                        'template-item' => 'dashboard/item-order-countpaystatusdeliverystatus-standard'
                    ]
                ]
            ]
        ],
    ],

    'client' => [
        'html' => [
            'basket' => [
                'cache' => [
                    // 'enable' => false, // Disable basket content caching for development
                ],
            ],
            'common' => [
                'content' => [
                    // 'baseurl' => config( 'app.url' ) . '/',
                ],
                'template' => [
                    // 'baseurl' => public_path( 'packages/aimeos/shop/themes/elegance' ),
                ],
            ],
            'checkout' => [
                'confirm' => [
                    'name' => 'EbdaaClientStandard'
                ]
            ],
            'catalog' => [
                'lists' => [
                    'sort' => 'relevance'
                ]
            ]

        ],
        'jsonapi' => [
            'product' => [
                'levels' => 3
            ],
            'debug' => 0,
            'order' => [
                'name' => 'EbdaaClientStandard'
            ],
            'basket' => [
                'product' => [
                    'name' => 'EbdaaBasketProductStandard'
                ]
            ],
        ],
    ],

    'controller' => [
        'jobs' => [
            'product' => [
                'import' => [
                    'csv' => [
                        'location' => 'storage/csv_uploads',
                        'skip-lines' => 2,
                        'container' => [
                            'type' => 'Directory',
                            'content' => 'CSV',
                        ],
                        'mapping' => [
                            'item' => array(
                                0 => 'product.code', // e.g. unique EAN code
                                1 => 'product.label', // UTF-8 encoded text, also used as product name
                                2 => 'product.type', // type of the product, e.g. "default" or "selection"
                                3 => 'product.status', // enabled (1) or disabled (0)
                                4 => 'product.url',
                                5 => 'product.datestart',
                                6 => 'product.dateend',
                                7 => 'product.target',
                            ),
                            'media' => array(
                                8 => 'media.url',
                                9 => 'media.url',
                                10 => 'media.url',
                                11 => 'media.url',
                                12 => 'media.url',
                                13 => 'media.url',
                                14 => 'media.url',
                                15 => 'media.url',
                                16 => 'media.url',
                                17 => 'media.url',
                                18 => 'media.url',
                                19 => 'media.url',
                                20 => 'media.url',
                                21 => 'media.url',
                                22 => 'media.url',
                            ),
                            'text' => array(
                                23 => 'text.type',
                                24 => 'text.content',
                                25 => 'text.languageid',
                                26 => 'text.label',
                                27 => 'text.status',

                                28 => 'text.type',
                                29 => 'text.content',
                                30 => 'text.languageid',
                                31 => 'text.label',
                                32 => 'text.status',

                                33 => 'text.type',
                                34 => 'text.content',
                                35 => 'text.languageid',
                                36 => 'text.label',
                                37 => 'text.status',

                                38 => 'text.type',
                                39 => 'text.content',
                                40 => 'text.languageid',
                                41 => 'text.label',
                                42 => 'text.status',

                                43 => 'text.type',
                                44 => 'text.content',
                                45 => 'text.languageid',
                                46 => 'text.label',
                                47 => 'text.status',

                                48 => 'text.type',
                                49 => 'text.content',
                                50 => 'text.languageid',
                                51 => 'text.label',
                                52 => 'text.status',

                                53 => 'text.type',
                                54 => 'text.content',
                                55 => 'text.languageid',
                                56 => 'text.label',
                                57 => 'text.status',
                            ),
                            'price' => array(
                                58 => 'price.currencyid',
                                59 => 'price.quantity',
                                60 => 'price.value',
                                61 => 'price.taxrate',
                                62 => 'price.rebate',
                                63 => 'price.costs',
                                64 => 'price.status',
                                65 => 'price.cost_price',
                                66 => 'price.commission',
                            ),
                            'stock' => array(
                                67 => 'stock.stocklevel',
                                68 => 'stock.type',
                                69 => 'stock.dateback',
                                70 => 'stock.timeframe',
                            ),
                            'catalog' => array(
                                71 => 'catalog.code',
                                72 => 'catalog.code',
                                73 => 'catalog.code',
                                74 => 'catalog.code',
                                75 => 'catalog.code',
                                76 => 'catalog.code',
                                77 => 'catalog.code',
                                78 => 'catalog.code',
                                79 => 'catalog.code',
                                80 => 'catalog.code',
                            ),
                            'property' => array(
                                81 => 'product.property.type',
                                82 => 'product.property.languageid',
                                83 => 'product.property.value',

                                84 => 'product.property.type',
                                85 => 'product.property.languageid',
                                86 => 'product.property.value',

                                87 => 'product.property.type',
                                88 => 'product.property.languageid',
                                89 => 'product.property.value',

                                90 => 'product.property.type',
                                91 => 'product.property.languageid',
                                92 => 'product.property.value',

                                93 => 'product.property.type',
                                94 => 'product.property.languageid',
                                95 => 'product.property.value',

                                96 => 'product.property.type',
                                97 => 'product.property.languageid',
                                98 => 'product.property.value',

                                99 => 'product.property.type',
                                100 => 'product.property.languageid',
                                101 => 'product.property.value',

                                102 => 'product.property.type',
                                103 => 'product.property.languageid',
                                104 => 'product.property.value',

                                105 => 'product.property.type',
                                106 => 'product.property.languageid',
                                107 => 'product.property.value',

                                108 => 'product.property.type',
                                109 => 'product.property.languageid',
                                110 => 'product.property.value',

                                111 => 'product.property.type',
                                112 => 'product.property.languageid',
                                113 => 'product.property.value',

                                114 => 'product.property.type',
                                115 => 'product.property.languageid',
                                116 => 'product.property.value',

                                117 => 'product.property.type',
                                118 => 'product.property.languageid',
                                119 => 'product.property.value',

                                120 => 'product.property.type',
                                121 => 'product.property.languageid',
                                122 => 'product.property.value',

                                123 => 'product.property.type',
                                124 => 'product.property.languageid',
                                125 => 'product.property.value',

                                126 => 'product.property.type',
                                127 => 'product.property.languageid',
                                128 => 'product.property.value',

                                129 => 'product.property.type',
                                130 => 'product.property.languageid',
                                131 => 'product.property.value',

                                132 => 'product.property.type',
                                133 => 'product.property.languageid',
                                134 => 'product.property.value',

                                135 => 'product.property.type',
                                136 => 'product.property.languageid',
                                137 => 'product.property.value',

                                138 => 'product.property.type',
                                139 => 'product.property.languageid',
                                140 => 'product.property.value',

                                141 => 'product.property.type',
                                142 => 'product.property.languageid',
                                143 => 'product.property.value',

                                144 => 'product.property.type',
                                145 => 'product.property.languageid',
                                146 => 'product.property.value',

                                147 => 'product.property.type',
                                148 => 'product.property.languageid',
                                149 => 'product.property.value',

                                150 => 'product.property.type',
                                151 => 'product.property.languageid',
                                152 => 'product.property.value',

                                153 => 'product.property.type',
                                154 => 'product.property.languageid',
                                155 => 'product.property.value',

                                156 => 'product.property.type',
                                157 => 'product.property.languageid',
                                158 => 'product.property.value',

                                159 => 'product.property.type',
                                160 => 'product.property.languageid',
                                161 => 'product.property.value',

                                162 => 'product.property.type',
                                163 => 'product.property.languageid',
                                164 => 'product.property.value',

                                165 => 'product.property.type',
                                166 => 'product.property.languageid',
                                167 => 'product.property.value',

                                168 => 'product.property.type',
                                169 => 'product.property.languageid',
                                170 => 'product.property.value',

                                171 => 'product.property.type',
                                172 => 'product.property.languageid',
                                173 => 'product.property.value',

                                174 => 'product.property.type',
                                175 => 'product.property.languageid',
                                176 => 'product.property.value',

                                177 => 'product.property.type',
                                178 => 'product.property.languageid',
                                179 => 'product.property.value',

                                180 => 'product.property.type',
                                181 => 'product.property.languageid',
                                182 => 'product.property.value',

                                183 => 'product.property.type',
                                184 => 'product.property.languageid',
                                185 => 'product.property.value',

                                186 => 'product.property.type',
                                187 => 'product.property.languageid',
                                188 => 'product.property.value',

                                189 => 'product.property.type',
                                190 => 'product.property.languageid',
                                191 => 'product.property.value',

                                192 => 'product.property.type',
                                193 => 'product.property.languageid',
                                194 => 'product.property.value',

                                195 => 'product.property.type',
                                196 => 'product.property.languageid',
                                197 => 'product.property.value',

                                198 => 'product.property.type',
                                199 => 'product.property.languageid',
                                200 => 'product.property.value',

                                201 => 'product.property.type',
                                202 => 'product.property.languageid',
                                203 => 'product.property.value',

                                204 => 'product.property.type',
                                205 => 'product.property.languageid',
                                206 => 'product.property.value',

                                207 => 'product.property.type',
                                208 => 'product.property.languageid',
                                209 => 'product.property.value',

                                210 => 'product.property.type',
                                211 => 'product.property.languageid',
                                212 => 'product.property.value',

                                213 => 'product.property.type',
                                214 => 'product.property.languageid',
                                215 => 'product.property.value',

                                216 => 'product.property.type',
                                217 => 'product.property.languageid',
                                218 => 'product.property.value',

                                219 => 'product.property.type',
                                220 => 'product.property.languageid',
                                221 => 'product.property.value',

                                222 => 'product.property.type',
                                223 => 'product.property.languageid',
                                224 => 'product.property.value',

                                225 => 'product.property.type',
                                226 => 'product.property.languageid',
                                227 => 'product.property.value',

                                228 => 'product.property.type',
                                229 => 'product.property.languageid',
                                230 => 'product.property.value',
                            ),
                            'attribute' => array(
                                231 => 'attribute.type',
                                232 => 'attribute.code',
                                233 => 'attribute.label',
                                234 => 'product.lists.type',

                                235 => 'attribute.type',
                                236 => 'attribute.code',
                                237 => 'attribute.label',
                                238 => 'product.lists.type',

                                239 => 'attribute.type',
                                240 => 'attribute.code',
                                241 => 'attribute.label',
                                242 => 'product.lists.type',

                                243 => 'attribute.type',
                                244 => 'attribute.code',
                                245 => 'attribute.label',
                                246 => 'product.lists.type',

                                247 => 'attribute.type',
                                248 => 'attribute.code',
                                249 => 'attribute.label',
                                250 => 'product.lists.type',


                                251 => 'attribute.type',
                                252 => 'attribute.code',
                                253 => 'attribute.label',
                                254 => 'product.lists.type',


                                255 => 'attribute.type',
                                256 => 'attribute.code',
                                257 => 'attribute.label',
                                258 => 'product.lists.type',


                                259 => 'attribute.type',
                                260 => 'attribute.code',
                                261 => 'attribute.label',
                                262 => 'product.lists.type',


                                263 => 'attribute.type',
                                264 => 'attribute.code',
                                265 => 'attribute.label',
                                266 => 'product.lists.type',

                                267 => 'attribute.type',
                                268 => 'attribute.code',
                                269 => 'attribute.label',
                                270 => 'product.lists.type',
                            ),
                        ]
                    ]
                ]
            ]
        ]
    ],

    'i18n' => [
    ],

    'madmin' => [
        'cache' => [
            'manager' => [
                'name' => 'None', // Disable caching for development
            ],
        ],
        'log' => [
            'manager' => [
                'standard' => [
                    // 'loglevel' => 7, // Enable debug logging into madmin_log table
                ],
            ],
        ],
    ],

    'mshop' => [
        'locale' => [
            'manager' => [
                'standard' => [
                    'sitelevel' => 3
                ]
            ]
        ],
        'order' => [
            'manager' => [
                'name' => 'EbdaaOrderManager',
                'base' => [
                    'name' => 'Ebdaa',
                    'service' => [
                        'name' => 'EbdaaOrderManagerBaseService'
                    ]
                ]
            ]
        ],
        'price' => [
            'manager' => [
                'name' => 'EbdaaPriceManager'
            ]
        ],
        'product' => [
            'manager' => [
                'name' => 'EbdaaProductManager',
            ]
        ]
    ],


    'command' => [
    ],

    'frontend' => [
    ],

    'backend' => [
    ],
];
