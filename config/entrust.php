<?php

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Entrust Role Model
    |--------------------------------------------------------------------------
    |
    | This is the Role model used by Entrust to create correct relations.  Update
    | the role if it is in a different namespace.
    |
    */
    'role' => 'App\Role',

    /*
    |--------------------------------------------------------------------------
    | Entrust Roles Table
    |--------------------------------------------------------------------------
    |
    | This is the roles table used by Entrust to save roles to the database.
    |
    */
    'roles_table' => 'roles',

    /*
    |--------------------------------------------------------------------------
    | Entrust Permission Model
    |--------------------------------------------------------------------------
    |
    | This is the Permission model used by Entrust to create correct relations.
    | Update the permission if it is in a different namespace.
    |
    */
    'permission' => 'App\Permission',

    /*
    |--------------------------------------------------------------------------
    | Entrust Permissions Table
    |--------------------------------------------------------------------------
    |
    | This is the permissions table used by Entrust to save permissions to the
    | database.
    |
    */
    'permissions_table' => 'permissions',

    /*
    |--------------------------------------------------------------------------
    | Entrust permission_role Table
    |--------------------------------------------------------------------------
    |
    | This is the permission_role table used by Entrust to save relationship
    | between permissions and roles to the database.
    |
    */
    'permission_role_table' => 'permission_role',

    /*
    |--------------------------------------------------------------------------
    | Entrust admin_role Table
    |--------------------------------------------------------------------------
    |
    | This is the admin_role table used by Entrust to save assigned roles to the
    | database.
    |
    */
    'admin_role_table' => 'admin_role',

    /*
    |--------------------------------------------------------------------------
    | User Foreign key on Entrust's admin_role Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'user_foreign_key' => 'admin_id',

    /*
    |--------------------------------------------------------------------------
    | Role Foreign key on Entrust's admin_role Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'role_foreign_key' => 'role_id',

];
