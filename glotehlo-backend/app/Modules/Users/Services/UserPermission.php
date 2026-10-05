<?php
namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;

use App\Modules\Users\Roles\UserRoles;



class UserPermission {
public function can(User $user, string $action, $resource = null): bool
{
    if (!$user->active) {
        return false;
    }

    $role = $user->role;

    $permissions = UserRoles::PERMISSIONS[$role] ?? [];

    if (!in_array($action, $permissions)) {
        return false;
    }

    if ($resource === null) {
        return true;
    }

    if ($user->role === 'admin') {
        return true;
    }

    if ($user->role === 'intern_head') {
        return $resource->department_id === $user->department_id;
    }

    if ($user->role === 'intern') {
        return $resource->user_id === $user->id;
    }

    return false;
}

}





