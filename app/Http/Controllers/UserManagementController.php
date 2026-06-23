<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\BulkDeleteUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class UserManagementController extends Controller
{
    public function index(Request $request): View
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
        $users = $query->paginate($perPage)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function bulkDestroy(BulkDeleteUserRequest $request): RedirectResponse
    {
        User::whereIn('id', $request->validated('ids'))->delete();

        return back()->with('success', 'Selected users were deleted successfully.');
    }

    public function restore(int $user): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($user);
        $user->restore();

        return back()->with('success', 'User restored successfully.');
    }

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request->status, $request->name), 'users_'.now()->format('Ymd_His').'.xlsx');
    }
}
