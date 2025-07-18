<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function isHeadmasterOrIT()
    {
        $user = auth()->user();
        if (!$user) return false;
        $role = $user->role->name ?? null;
        return in_array($role, ['headmaster', 'it', 'it_department', 'it-staff', 'it_department_user']);
    }
}
