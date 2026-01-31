<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

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
}
