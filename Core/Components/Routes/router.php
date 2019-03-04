<?php

Route::middleware(['transaction'])->group(function () {
    Route::get('login', 'AuthAdminController@login')->name('login');

    Route::middleware(['authenticate'])->group(function () {
        Route::resource('user', 'UserAdminController')->only(['index', 'store', 'update', 'show']);
        Route::resource('role', 'RoleAdminController')->only(['index', 'store', 'update']);
        Route::resource('permission', 'PermissionAdminController')->only(['index', 'store', 'update']);

        Route::post('roleEmpowerment/{id}', 'PermissionAdminController@roleEmpowerment')->name('role.empowerment');
        Route::post('userEmpowerment/{id}', 'PermissionAdminController@userEmpowerment')->name('user.empowerment');
        Route::post('syncRoles/{id}', 'PermissionAdminController@syncRoles')->name('sync.roles');
        Route::post('logout', 'AuthAdminController@logout')->name('logout');
    });
});