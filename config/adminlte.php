<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'GSSoftware',
    'title_prefix' => 'GSSoftware | ',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => true,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => 'Eletro <b>Barbosa</b>',
    'logo_img' => 'vendor/adminlte/dist/img/logo.jpeg',
    'logo_img_class' => 'brand-image elevation-4', // imagem circular
    'logo_img_xl' => '', // vendor/adminlte/dist/img/AdminLTELogo2.png
    'logo_img_xl_class' => 'brand-image-xl',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logo-grande.png',
            'alt' => 'GSSoftware',
            'effect' => 'animation__shake',
            'width' => 300,
            'height' => 180,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null, // modo escuro

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg', // sidebar minimizada
    'sidebar_collapse' => true,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 30,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => false,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu',
        ],
        [
            'text' => 'blog',
            'url'  => 'admin/blog',
            'can'  => 'manage-blog',
        ],
        ['header' => 'GSSoftware'],
        [
            'text' => 'Dashboard',
            'url'  => 'dashboard',
            'icon' => 'fas fa-chart-pie'
        ], 
        [
            'text'    => 'Estoque',
            'icon'    => 'fas fa-clipboard-list',
            'submenu' => [
                [
                    'text' => 'Materiais',
                    'url'  => 'materiais',
                    'icon' => 'fas fa-plug',
                    'can'  => 'ESTOQUE_MATERIAIS'
                ], 
                [
                    'text' => 'Retiradas / Devoluções',
                    'url'  => 'materiais/retirada-devolucao',
                    'icon' => 'fas fa-exchange-alt',
                    'can'  => 'ESTOQUE_RETIRADA'
                ],
                [
                    'text' => 'Lista de Materiais',
                    'url'  => 'materiais/lista',
                    'icon' => 'fas fa-list',
                    'can'  => 'LISTA_MATERIAIS'
                ],   
                [
                    'text' => 'Produtos/Serviços',
                    'url'  => 'produto',
                    'icon' => 'fas fa-tags',
                ], 
                [
                    'text' => 'Cardápio',
                    'url'  => 'cardapio',
                    'icon' => 'fas fa-hamburger',
                    'can'  => 'CARDAPIO'
                ], 
                [
                    'text' => 'Relatórios de Estoque',
                    'url'  => 'relatorios/material',
                    'icon' => 'fas fa-print',
                    'can'  => 'ESTOQUE_RELATORIO'
                ], 
            ]
        ], 
        [
            'text'    => 'Compras',
            'icon'    => 'fas fa-shopping-cart',
            'submenu' => [
                [
                    'text' => 'Pedido de Compra',
                    'url'  => 'compras',
                    'icon' => 'fas fa-shopping-basket',
                    'can'  => 'COMPRAS_PEDIDO'
                ], 
                [
                    'text' => 'Orçamento',
                    'url'  => 'orcamento',
                    'icon' => 'fas fa-file-alt',
                    'can'  => 'ORCAMENTO'
                ],  
                [
                    'text' => 'PDV',
                    'url'  => 'pdv',
                    'icon' => 'fas fa-shopping-cart',
                    'can'  => 'PDV'
                ], 
                [
                    'text' => 'Relatórios de Compras',
                    'url'  => 'relatorios/compras',
                    'icon' => 'fas fa-print',
                    'can'  => 'COMPRAS_RELATORIO'
                ], 
            ]
        ], 
        [
            'text'    => 'Financeiro',
            'icon'    => 'fas fa-hand-holding-usd',
            'submenu' => [  
                [
                    'text' => 'Contas a Pagar',
                    'url'  => 'contaspagar',
                    'icon' => 'fas fa-receipt',
                    'can'  => 'FINANCEIRO_CPG'
                ],  
                [
                    'text' => 'Contas a Receber',
                    'url'  => 'financeiro/contasreceber',
                    'icon' => 'fas fa-file-invoice-dollar',
                    'can'  => 'FINANCEIRO_CRB'
                ],
                [
                    'text' => 'Despesas de Projeto',
                    'url'  => 'financeiro/despesaobra',
                    'icon' => 'fas fa-hard-hat',
                    'can'  => 'FINANCEIRO_DESPESAS_PROJETO'
                ],
                [
                    'text' => 'Despesas da Empresa',
                    'url'  => 'financeiro/despesaempresa',
                    'icon' => 'fas fa-building',
                    'can'  => 'FINANCEIRO_DESPESAS_EMPRESA'
                ],
                // [
                //     'text' => 'Impostos e Tributos',
                //     'url'  => 'financeiro/impostos-tributos',
                //     'icon' => 'fas fa-percentage'
                // ],
                [
                    'text' => 'Diárias',
                    'url'  => 'financeiro/diaria',
                    'icon' => 'fas fa-comment-dollar',
                    'can'  => 'FINANCEIRO_DIARIA'
                ],
                [
                    'text' => 'Folha de Pagamento',
                    'url'  => 'financeiro/folha-pagamento',
                    'icon' => 'fas fa-wallet',
                    'can'  => 'FINANCEIRO_FOLHA'
                ],
                [
                    'text' => 'Faturamento',
                    'url'  => 'financeiro/faturamento',
                    'icon' => 'fas fa-dollar-sign',
                    'can'  => 'FINANCEIRO_FATURAMENTO'
                ],
                [
                    'text' => 'Relatórios Financeiros',
                    'url'  => 'financeiro/relatorios',
                    'icon' => 'fas fa-print',
                    'can'  => 'INATIVO'
                ], 
            ]
        ], 
        [
            'text'    => 'Departamento Pessoal',
            'icon'    => 'fas fa-building',
            'submenu' => [
                [
                    'text' => 'Agenda',
                    'url'  => 'agenda',
                    'icon' => 'fas fa-calendar-alt',
                    'can'  => 'DEPARTAMENTO_PESSOAL_AGENDA'
                ],   
                [
                    'text' => 'Projetos',
                    'url'  => 'projeto',
                    'icon' => 'fas fa-file-powerpoint',
                    'can'  => 'DEPARTAMENTO_PROJETOS'
                ],  
                [
                    'text' => 'Pessoas',
                    'url'  => 'pessoas',
                    'icon' => 'fas fa-user-edit',
                    'can'  => 'DEPARTAMENTO_PESSOAL_PESSOAS'
                ],   
                [
                    'text' => 'Funcionários',
                    'url'  => 'gestaoempresa/funcionarios',
                    'icon' => 'fas fa-id-card',
                    'can'  => 'DEPARTAMENTO_PESSOAL_FUNCIONARIO'
                ],   
                [
                    'text' => 'Documentos',
                    'url'  => 'gestaoempresa/documento',
                    'icon' => 'fas fa-file-alt',
                    'can'  => 'DEPARTAMENTO_PESSOAL_DOCUMENTO'
                ],    
                [
                    'text' => 'Controle de Ponto',
                    'url'  => 'controleponto',
                    'icon' => 'fas fa-clock',
                    'can'  => 'DEPARTAMENTO_PESSOAL_PONTO'
                ],   
                [
                    'text' => 'Uniformes e EPI',
                    'url'  => 'gestaoempresa/uniformes',
                    'icon' => 'fas fa-tshirt',
                    'can'  => 'DEPARTAMENTO_PESSOAL_UNIFORMES'
                ], 
                [
                    'text' => 'Ordem Serviço',
                    'url'  => 'ordemServico',
                    'icon' => 'fas fa-file-contract',
                    'can' => 'ORDEM_SERVICO'
                ],     
                [
                    'text' => 'Usuários',
                    'url'  => 'usuarios',
                    'icon' => 'fas fa-users',
                    'can'  => 'DEPARTAMENTO_PESSOAL_USUARIOS'
                ]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
