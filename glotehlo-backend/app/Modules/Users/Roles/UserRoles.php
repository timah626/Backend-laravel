<?php

namespace App\Modules\Users\Roles;

class UserRoles
{
    private const INTERN = [
        'clock_in',
        'clock_out',
        'view_own_history',
        'change_own_password',
        'update_own_photo',
    ];

    public const PERMISSIONS = [
        'intern' => self::INTERN,

        'intern_head' => [
            ...self::INTERN,
            'view_department_today',
            'view_department_history',
            'confirm_record',
            'export_csv',
            'list_interns'
        ],

        'admin' => [
            'view_all_history',
            'export_csv',
            'manage_sites',
            'manage_departments',
            'print_department_qr',
            'manage_office_networks',
            'review_pending',
            'create_user',
            'reset_password',
            'manage_settings',
        ],
    ];
}

