<?php

namespace App\Services;

use App\Repositories\FatherScheduleRepository;

class FatherScheduleService
{
    public function __construct(
        protected FatherScheduleRepository $fatherScheduleRepository,
    ) {}

    public function index($father_id = null)
    {
        return $this->fatherScheduleRepository->index($father_id);
    }

    public function show($id)
    {
        $schedule = $this->fatherScheduleRepository->show($id);
        $schedule->load('father');

        return $schedule;
    }

    public function store($input)
    {
        return $this->fatherScheduleRepository->store($input);
    }

    public function delete($id)
    {
        return $this->fatherScheduleRepository->delete($id);
    }

    public function getSchedulesByDay($father_id, $day)
    {
        return $this->fatherScheduleRepository->getSchedulesByDay($father_id, $day);
    }
}
