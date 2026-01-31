<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    public function index($search = null)
    {
        $users = $this->userRepository->index($search);

        // Load relationships
        $users->load(['roles', 'fathers']);

        return $users;
    }

    public function show($id)
    {
        $user = $this->userRepository->show($id);
        $user->load(['atrafAsUser', 'roles', 'fathers']);

        return $user;
    }
}
