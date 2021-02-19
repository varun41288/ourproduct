<?php
return [
    'administrator' => [
        'type' => 1,
        'children' => [
            'customer_module_admin_access',
            'products_module_admin_access',
            'supplier_module_admin_access',
            'invoices_module_admin_access',
            '/users/profile',
            '/reports/index',
            '/reports/*',
            'purchases_module_admin_access',
            'estimations_module_admin_access',
            'modules_module_admin_permission',
        ],
    ],
    '/customers/index' => [
        'type' => 2,
    ],
    '/customers/view' => [
        'type' => 2,
    ],
    '/customers/create' => [
        'type' => 2,
    ],
    '/customers/update' => [
        'type' => 2,
    ],
    '/customers/delete' => [
        'type' => 2,
    ],
    '/customers/*' => [
        'type' => 2,
    ],
    'customer_module_admin_access' => [
        'type' => 2,
        'children' => [
            '/customers/index',
            '/customers/view',
            '/customers/create',
            '/customers/update',
            '/customers/delete',
            '/customers/*',
        ],
    ],
    '/admin/assignment/index' => [
        'type' => 2,
    ],
    '/admin/assignment/view' => [
        'type' => 2,
    ],
    '/admin/assignment/assign' => [
        'type' => 2,
    ],
    '/admin/assignment/revoke' => [
        'type' => 2,
    ],
    '/admin/assignment/*' => [
        'type' => 2,
    ],
    '/admin/default/index' => [
        'type' => 2,
    ],
    '/admin/default/*' => [
        'type' => 2,
    ],
    '/admin/menu/index' => [
        'type' => 2,
    ],
    '/admin/menu/view' => [
        'type' => 2,
    ],
    '/admin/menu/create' => [
        'type' => 2,
    ],
    '/admin/menu/update' => [
        'type' => 2,
    ],
    '/admin/menu/delete' => [
        'type' => 2,
    ],
    '/admin/menu/*' => [
        'type' => 2,
    ],
    '/admin/permission/index' => [
        'type' => 2,
    ],
    '/admin/permission/view' => [
        'type' => 2,
    ],
    '/admin/permission/create' => [
        'type' => 2,
    ],
    '/admin/permission/update' => [
        'type' => 2,
    ],
    '/admin/permission/delete' => [
        'type' => 2,
    ],
    '/admin/permission/assign' => [
        'type' => 2,
    ],
    '/admin/permission/remove' => [
        'type' => 2,
    ],
    '/admin/permission/*' => [
        'type' => 2,
    ],
    '/admin/role/index' => [
        'type' => 2,
    ],
    '/admin/role/view' => [
        'type' => 2,
    ],
    '/admin/role/create' => [
        'type' => 2,
    ],
    '/admin/role/update' => [
        'type' => 2,
    ],
    '/admin/role/delete' => [
        'type' => 2,
    ],
    '/admin/role/assign' => [
        'type' => 2,
    ],
    '/admin/role/remove' => [
        'type' => 2,
    ],
    '/admin/role/*' => [
        'type' => 2,
    ],
    '/admin/route/index' => [
        'type' => 2,
    ],
    '/admin/route/create' => [
        'type' => 2,
    ],
    '/admin/route/assign' => [
        'type' => 2,
    ],
    '/admin/route/remove' => [
        'type' => 2,
    ],
    '/admin/route/refresh' => [
        'type' => 2,
    ],
    '/admin/route/*' => [
        'type' => 2,
    ],
    '/admin/rule/index' => [
        'type' => 2,
    ],
    '/admin/rule/view' => [
        'type' => 2,
    ],
    '/admin/rule/create' => [
        'type' => 2,
    ],
    '/admin/rule/update' => [
        'type' => 2,
    ],
    '/admin/rule/delete' => [
        'type' => 2,
    ],
    '/admin/rule/*' => [
        'type' => 2,
    ],
    '/admin/user/index' => [
        'type' => 2,
    ],
    '/admin/user/view' => [
        'type' => 2,
    ],
    '/admin/user/delete' => [
        'type' => 2,
    ],
    '/admin/user/login' => [
        'type' => 2,
    ],
    '/admin/user/logout' => [
        'type' => 2,
    ],
    '/admin/user/signup' => [
        'type' => 2,
    ],
    '/admin/user/request-password-reset' => [
        'type' => 2,
    ],
    '/admin/user/reset-password' => [
        'type' => 2,
    ],
    '/admin/user/change-password' => [
        'type' => 2,
    ],
    '/admin/user/activate' => [
        'type' => 2,
    ],
    '/admin/user/*' => [
        'type' => 2,
    ],
    '/admin/*' => [
        'type' => 2,
    ],
    '/users/index' => [
        'type' => 2,
    ],
    '/users/view' => [
        'type' => 2,
    ],
    '/users/create' => [
        'type' => 2,
    ],
    '/users/update' => [
        'type' => 2,
    ],
    '/users/delete' => [
        'type' => 2,
    ],
    '/users/*' => [
        'type' => 2,
    ],
    '/debug/default/db-explain' => [
        'type' => 2,
    ],
    '/debug/default/index' => [
        'type' => 2,
    ],
    '/debug/default/view' => [
        'type' => 2,
    ],
    '/debug/default/toolbar' => [
        'type' => 2,
    ],
    '/debug/default/download-mail' => [
        'type' => 2,
    ],
    '/debug/default/*' => [
        'type' => 2,
    ],
    '/debug/user/set-identity' => [
        'type' => 2,
    ],
    '/debug/user/reset-identity' => [
        'type' => 2,
    ],
    '/debug/user/*' => [
        'type' => 2,
    ],
    '/debug/*' => [
        'type' => 2,
    ],
    '/gii/default/index' => [
        'type' => 2,
    ],
    '/gii/default/view' => [
        'type' => 2,
    ],
    '/gii/default/preview' => [
        'type' => 2,
    ],
    '/gii/default/diff' => [
        'type' => 2,
    ],
    '/gii/default/action' => [
        'type' => 2,
    ],
    '/gii/default/*' => [
        'type' => 2,
    ],
    '/gii/*' => [
        'type' => 2,
    ],
    '/site/error' => [
        'type' => 2,
    ],
    '/site/captcha' => [
        'type' => 2,
    ],
    '/site/index' => [
        'type' => 2,
    ],
    '/site/login' => [
        'type' => 2,
    ],
    '/site/logout' => [
        'type' => 2,
    ],
    '/site/contact' => [
        'type' => 2,
    ],
    '/site/about' => [
        'type' => 2,
    ],
    '/site/*' => [
        'type' => 2,
    ],
    '/*' => [
        'type' => 2,
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'customer_module_admin_access',
            'supplier_module_admin_access',
            'products_module_admin_access',
            'invoices_module_admin_access',
            '/users/profile',
            '/reports/index',
            '/reports/*',
            'purchases_module_admin_access',
            'estimations_module_admin_access',
        ],
    ],
    '/suppliers/index' => [
        'type' => 2,
    ],
    '/suppliers/view' => [
        'type' => 2,
    ],
    '/suppliers/create' => [
        'type' => 2,
    ],
    '/suppliers/update' => [
        'type' => 2,
    ],
    '/suppliers/delete' => [
        'type' => 2,
    ],
    '/suppliers/*' => [
        'type' => 2,
    ],
    'supplier_module_admin_access' => [
        'type' => 2,
        'children' => [
            '/suppliers/index',
            '/suppliers/view',
            '/suppliers/create',
            '/suppliers/update',
            '/suppliers/delete',
            '/suppliers/*',
        ],
    ],
    '/products/index' => [
        'type' => 2,
    ],
    '/products/view' => [
        'type' => 2,
    ],
    '/products/create' => [
        'type' => 2,
    ],
    '/products/update' => [
        'type' => 2,
    ],
    '/products/delete' => [
        'type' => 2,
    ],
    '/products/*' => [
        'type' => 2,
    ],
    'products_module_admin_access' => [
        'type' => 2,
        'children' => [
            '/products/index',
            '/products/view',
            '/products/create',
            '/products/update',
            '/products/delete',
            '/products/*',
        ],
    ],
    '/invoices/index' => [
        'type' => 2,
    ],
    '/invoices/view' => [
        'type' => 2,
    ],
    '/invoices/create' => [
        'type' => 2,
    ],
    '/invoices/update' => [
        'type' => 2,
    ],
    '/invoices/delete' => [
        'type' => 2,
    ],
    '/invoices/*' => [
        'type' => 2,
    ],
    'invoices_module_admin_access' => [
        'type' => 2,
        'children' => [
            '/invoices/index',
            '/invoices/view',
            '/invoices/create',
            '/invoices/update',
            '/invoices/delete',
            '/invoices/*',
        ],
    ],
    '/users/profile' => [
        'type' => 2,
    ],
    '/reports/index' => [
        'type' => 2,
    ],
    '/reports/*' => [
        'type' => 2,
    ],
    '/customers/search' => [
        'type' => 2,
    ],
    '/invoices/export' => [
        'type' => 2,
    ],
    '/products/search' => [
        'type' => 2,
    ],
    '/purchases/index' => [
        'type' => 2,
    ],
    '/purchases/view' => [
        'type' => 2,
    ],
    '/purchases/create' => [
        'type' => 2,
    ],
    '/purchases/update' => [
        'type' => 2,
    ],
    '/purchases/delete' => [
        'type' => 2,
    ],
    '/purchases/export' => [
        'type' => 2,
    ],
    '/purchases/*' => [
        'type' => 2,
    ],
    'purchases_module_admin_access' => [
        'type' => 2,
        'children' => [
            '/products/search',
            '/purchases/index',
            '/purchases/view',
            '/purchases/create',
            '/purchases/update',
            '/purchases/delete',
            '/purchases/export',
            '/purchases/*',
        ],
    ],
    '/estimations/index' => [
        'type' => 2,
    ],
    '/estimations/view' => [
        'type' => 2,
    ],
    '/estimations/create' => [
        'type' => 2,
    ],
    '/estimations/update' => [
        'type' => 2,
    ],
    '/estimations/delete' => [
        'type' => 2,
    ],
    '/estimations/export' => [
        'type' => 2,
    ],
    '/estimations/*' => [
        'type' => 2,
    ],
    'estimations_module_admin_access' => [
        'type' => 2,
        'children' => [
            '/estimations/index',
            '/estimations/view',
            '/estimations/create',
            '/estimations/update',
            '/estimations/delete',
            '/estimations/export',
            '/estimations/*',
        ],
    ],
    '/modules/index' => [
        'type' => 2,
    ],
    '/modules/view' => [
        'type' => 2,
    ],
    '/modules/create' => [
        'type' => 2,
    ],
    '/modules/update' => [
        'type' => 2,
    ],
    '/modules/delete' => [
        'type' => 2,
    ],
    '/modules/*' => [
        'type' => 2,
    ],
    'modules_module_admin_permission' => [
        'type' => 2,
        'children' => [
            '/modules/index',
            '/modules/view',
            '/modules/create',
            '/modules/update',
            '/modules/delete',
            '/modules/*',
        ],
    ],
];
