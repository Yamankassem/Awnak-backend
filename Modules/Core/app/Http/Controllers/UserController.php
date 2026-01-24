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
    public function __construct(private UserService $service) {}

    public function index()
    {
        return response()->json(
            $this->service->paginate()
        );
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->service->create($request->validated(), request()->user()->id);

        return response()->json($user, 201);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = User::findOrFail($id);

        $user = $this->service->update($user, $request->validated(), request()->user()->id);

        return response()->json($user);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        //$this->service->delete($user, auth()->id());
        $this->service->delete(
            $user,
            request()->user()->id
        );

        return response()->json(['ok' => true]);
    }

    public function assignRoles(AssignUserRolesRequest $request, int $id)
    {
        $user = User::findOrFail($id);

        $user = $this->service->syncRoles(
            $user,
            $request->validated()['roles'],
            request()->user()->id
        );

        return response()->json($user);
    }
}
