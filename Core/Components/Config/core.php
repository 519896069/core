<?php
return [
    /*
     * |-----------------------------------
     * |
     * | 路由配置
     * | 路由规则 [ method , path , Controller@action , name or resource method , ]
     * |-----------------------------------
     */
    'route' => [
        'load'   => false,
        'routes' => [
            [
                'middleware' => [],
                'router'     => [
                    ['post', 'login', 'AuthAdminController@login', 'login'],
                ],
            ],
            [
                'middleware' => ['authenticate'],
                'router'     => [
                    ['resource', 'user', 'UserAdminController', ['index', 'store', 'update', 'show'],],
                    ['resource', 'role', 'RoleAdminController', ['index', 'store', 'update'],],
                    ['resource', 'permission', 'PermissionAdminController', ['index', 'store', 'update'],],
                    ['post', 'roleEmpowerment/{id}', 'PermissionAdminController@roleEmpowerment', 'role.empowerment',],
                    ['post', 'userEmpowerment/{id}', 'PermissionAdminController@userEmpowerment', 'user.empowerment',],
                    ['post', 'syncRoles/{id}', 'PermissionAdminController@syncRoles', 'sync.roles',],
                    ['logout', 'syncRoles/{id}', 'AuthAdminController@logout', 'logout',],
                ]
            ]
        ],
    ],
];