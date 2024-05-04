<?php

namespace App\Http\Controllers;

use App\Http\Services\Permission\QueryPermissionService;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionsController extends Controller
{
    public function getPermissions(Request $request)
    {
        Gate::authorize('view', Permission::class);

        $service = new QueryPermissionService();

        return $service->getPermissions($request->all());
    }
}
