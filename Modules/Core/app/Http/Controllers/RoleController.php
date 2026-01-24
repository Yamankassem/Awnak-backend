<?php

namespace Modules\Core\Http\Controllers;


use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Modules\Core\Services\RoleService;
use Modules\Core\Http\Requests\StoreRoleRequest;
use Modules\Core\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    public function __construct(private RoleService $service) {}

    public function index()
    {
        return response()->json(
            $this->service->list()
        );
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->service->create($request->validated(),request()->user()->id);

        return response()->json($role, 201);
    }

    public function update(UpdateRoleRequest $request, int $id)
    {
        $role = Role::findOrFail($id);

        $role = $this->service->update($role, $request->validated(),request()->user()->id);

        return response()->json($role);
    }

    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);

        $this->service->delete($role,request()->user()->id);

        return response()->json(['ok' => true]);
    }
}
