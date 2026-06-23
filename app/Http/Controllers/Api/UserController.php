<?php

namespace App\Http\Controllers\Api;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulkDeleteUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->orderByDesc('created_at');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->string('name').'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        $perPage = max(1, min($request->integer('per_page', 15), 100));

        return UserResource::collection($query->paginate($perPage));
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $user = User::create($request->validated());

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $data = $request->validated();

        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted.'], Response::HTTP_NO_CONTENT);
    }

    public function bulkDestroy(BulkDeleteUserRequest $request)
    {
        User::whereIn('id', $request->validated('ids'))->delete();

        return response()->json(['message' => 'Users deleted.']);
    }

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request->status, $request->name), 'users_'.now()->format('Ymd_His').'.xlsx');
    }
}
