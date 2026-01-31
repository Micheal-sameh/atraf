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
        return $this->userRepository->index($search);
    }

    public function show($id)
    {
        $user = $this->userRepository->show($id);
        $user->load(['atrafAsUser']);

        return $user;
    }
}
