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

    
    /**
     * Retrieve a paginated list of roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = $this->service->list();

        return static::paginated(
            paginator: $roles,
            message: 'roles.listed'
        );
    }

    /**
     * Create a new role.
     *
     * @param StoreRoleRequest $request Validated role creation data.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreRoleRequest $request)
    {
        $role = $this->service->create($request->validated(), request()->user()->id);

        return static::success(
            data: $role,
            message: 'roles.created',
            status: 201
        );
    }

     /**
     * Update an existing role.
     *
     * @param UpdateRoleRequest $request Validated role update data.
     * @param int               $id      Role identifier.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(UpdateRoleRequest $request, int $id)
    {
        $role = Role::findOrFail($id);

        $role = $this->service->update($role, $request->validated(), request()->user()->id);

        return static::success(
            data: $role,
            message: 'roles.updated'
        );
    }

    /**
     * Delete a role.
     *
     * @param int $id Role identifier.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);

        $this->service->delete($role, request()->user()->id);

        return static::success(
            message: 'roles.deleted'
        );
    }
}
