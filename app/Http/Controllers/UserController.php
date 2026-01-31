<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * UserController
 *
 * Handles user management operations including viewing, searching,
 * and role assignment for authorized users.
 */
class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(Request $request)
    {
        $search = $request->search;
        $users = $this->userService->index($search);

        return view('users.index', compact('users', 'search'));
    }

    public function show($id)
    {
        $user = $this->userService->show($id);
        $atrafCount = $user->atrafAsUser->count();

        return view('users.show', compact('user', 'atrafCount'));
    }

    /**
     * Show the form for editing user roles
     *
     * @param  int  $id  User ID
     * @return \Illuminate\View\View
     */
    public function editRoles($id)
    {
        $user = $this->userService->show($id);
        $allRoles = Role::all();

        return view('users.edit-roles', compact('user', 'allRoles'));
    }

    /**
     * Update user roles
     *
     * @param  int  $id  User ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRoles(Request $request, $id)
    {
        $validated = $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')
            ->with('success', __('messages.roles_updated_successfully'));
    }
}
