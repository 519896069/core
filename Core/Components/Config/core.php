<?php

use Core\Core;

return [
    /*
     * |-----------------------------------
     * |
     * | 路由配置
     * | [
     * |    'middleware' => [],
     * |    'routes'     => [
     * |        [method, path, action, name or resource method],
     * |    ],
     * | ]
     * | 路由规则 [ method , path , Controller@action , name or resource method , ]
     * |-----------------------------------
     */
    'routes' => [
        'default' => [
            [
                'middleware' => [],
                'routes'     => [
                    [Core::POST, 'login', 'AuthAdminController@login', 'login'],
                ],
            ],
            [
                'middleware' => ['authenticate'],
                'routes'     => [
                    [Core::RESOURCE, 'user', 'UserAdminController', ['index', 'store', 'update', 'show'],],
                    [Core::RESOURCE, 'role', 'RoleAdminController', ['index', 'store', 'update'],],
                    [Core::RESOURCE, 'permission', 'PermissionAdminController', ['index', 'store', 'update'],],
                    [Core::POST, 'roleEmpowerment/{id}', 'PermissionAdminController@roleEmpowerment', 'role.empowerment',],
                    [Core::POST, 'userEmpowerment/{id}', 'PermissionAdminController@userEmpowerment', 'user.empowerment',],
                    [Core::POST, 'syncRoles/{id}', 'PermissionAdminController@syncRoles', 'sync.roles',],
                    [Core::POST, 'logout', 'AuthAdminController@logout', 'logout',],
                ]
            ]
        ],
    ],
];