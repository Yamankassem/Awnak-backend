<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Models\User;
use App\Http\Controllers\Controller;
use Modules\Core\Services\UserService;
use Modules\Core\Http\Requests\StoreUserRequest;
use Modules\Core\Http\Requests\UpdateUserRequest;
use Modules\Core\Http\Requests\AssignUserRolesRequest;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * @param UserService $service Handles user-related business logic.
     */
    public function __construct(private UserService $service) {}

    /**
     * Retrieve a paginated list of users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->service->paginate();

        return static::paginated(
            paginator: $users,
            message: 'users.listed'
        );
    }

    /**
     * Create a new user.
     *
     * @param StoreUserRequest $request Validated user creation data.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->service->create($request->validated(), request()->user()->id);

        return static::success(
            data: $user,
            message: 'users.created',
            status: 201
        );
    }

    /**
     * Update an existing user.
     *
     * @param UpdateUserRequest $request Validated user update data.
     * @param int               $id      User identifier.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->service->update($user, $request->validated(), request()->user()->id);

        return static::success(
            data: $user,
            message: 'users.updated'
        );
    }

    /**
     * Delete a user.
     *
     * @param int $id User identifier.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(User $user)
    {
        $this->service->delete(
            $user,
            request()->user()->id
        );

        return static::success(
            data: $user,
            message: 'users.deleted'
        );
    }

    /**
     * Assign roles to a user.
     *
     * @param AssignUserRolesRequest $request Validated role assignment data.
     * @param int                    $id      User identifier.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function assignRoles(AssignUserRolesRequest $request, User $user)
    {
        $user = $this->service->syncRoles(
            $user,
            $request->validated()['roles'],
            request()->user()->id
        );

        return static::success(
            data: $user,
            message: 'user.assigned.role'
        );
    }
}
