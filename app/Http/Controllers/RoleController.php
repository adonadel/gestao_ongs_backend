<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Services\Role\CreateRoleService;
use App\Http\Services\Role\DeleteRoleService;
use App\Http\Services\Role\QueryRoleService;
use App\Http\Services\Role\UpdateRoleService;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function create(RoleRequest $request)
    {
        Gate::authorize('create', Role::class);

        try {
            DB::beginTransaction();

            $service = new CreateRoleService();

            $role = $service->create($request->all());

            DB::commit();

            return $role;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function update(RoleRequest $request, int $id)
    {
        Gate::authorize('update', Role::class);

        try {
            DB::beginTransaction();

            $service = new UpdateRoleService();

            $updated = $service->update($request->all(), $id);

            DB::commit();

            return $updated;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        Gate::authorize('delete', Role::class);

        try {
            DB::beginTransaction();

            $service = new DeleteRoleService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Nível de permissão excluído com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function getRoles(Request $request)
    {
        Gate::authorize('view', Role::class);

        $service = new QueryRoleService();

        return $service->getRoles($request->all());
    }

    public function getRoleById(int $id)
    {
        Gate::authorize('view', Role::class);

        $service = new QueryRoleService();

        return $service->getRoleById($id);
    }
}
