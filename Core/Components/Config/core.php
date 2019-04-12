<?php

use Core\Core;

return [
    /*
     * |-----------------------------------
     * |
     * | 路由配置
     * | 路由规则 [ method , path , Controller@action , name or resource method , ]
     * |-----------------------------------
     */
    'route' => [
        'default' => [
            [
                'middleware' => [],
                'router'     => [
                    [Core::POST, 'login', 'AuthAdminController@login', 'login'],
                ],
            ],
            [
                'middleware' => ['authenticate'],
                'router'     => [
                    [Core::RESOURCE, 'user', 'UserAdminController', ['index', 'store', 'update', 'show'],],
                    [Core::RESOURCE, 'role', 'RoleAdminController', ['index', 'store', 'update'],],
                    [Core::RESOURCE, 'permission', 'PermissionAdminController', ['index', 'store', 'update'],],
                    [Core::POST, 'roleEmpowerment/{id}', 'PermissionAdminController@roleEmpowerment', 'role.empowerment',],
                    [Core::POST, 'userEmpowerment/{id}', 'PermissionAdminController@userEmpowerment', 'user.empowerment',],
                    [Core::POST, 'syncRoles/{id}', 'PermissionAdminController@syncRoles', 'sync.roles',],
                    [Core::POST, 'logout', 'syncRoles/{id}', 'AuthAdminController@logout', 'logout',],
                ]
            ]
        ],
    ],
];