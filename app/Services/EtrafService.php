<?php

namespace App\Services;

use App\Repositories\EtrafRepository;
use App\Repositories\FatherScheduleRepository;
use Carbon\Carbon;

class EtrafService
{
    public function __construct(
        protected EtrafRepository $etrafRepository,
        protected FatherScheduleRepository $fatherScheduleRepository,
    ) {}

    public function index($search = null, $status = null, $dateFrom = null, $dateTo = null)
    {
        $user = auth()->user();
        $userId = null;
        $fatherId = null;

        // If user role, show only their records
        if ($user->hasRole('user') && ! $user->hasRole('father') && ! $user->hasRole('admin')) {
            $userId = $user->id;
        }
        // If father role (not admin), show records as both father and user
        elseif ($user->hasRole('father') && ! $user->hasRole('admin')) {
            // We'll handle this in repository with OR condition
            $userId = $user->id;
            $fatherId = $user->id;
        }
        // Admin sees all

        return $this->etrafRepository->index($search, $status, $dateFrom, $dateTo, $userId, $fatherId);
    }

    public function show($id)
    {
        $etraf = $this->etrafRepository->show($id);
        $etraf->load(['father', 'user']);

        return $etraf;
    }

    public function store($input)
    {
        // Validate that date is not before today
        $date = Carbon::parse($input['date']);
        if ($date->isBefore(Carbon::today())) {
            throw new \Exception('Cannot create etraf for a date in the past');
        }

        // Check if father has schedule on this day
        $dayOfWeek = strtolower($date->format('l'));
        $schedules = $this->fatherScheduleRepository->getSchedulesByDay($input['father_id'], $dayOfWeek);

        if ($schedules->isEmpty()) {
            throw new \Exception('Father has no schedule on '.ucfirst($dayOfWeek));
        }

        // Validate time is within father's schedule
        $fromTime = Carbon::parse($input['from']);
        $toTime = Carbon::parse($input['to']);

        $validSlot = false;
        foreach ($schedules as $schedule) {
            $scheduleFrom = Carbon::parse($schedule->from);
            $scheduleTo = Carbon::parse($schedule->to);

            if ($fromTime->greaterThanOrEqualTo($scheduleFrom) && $toTime->lessThanOrEqualTo($scheduleTo)) {
                $validSlot = true;
                break;
            }
        }

        if (! $validSlot) {
            throw new \Exception('Selected time is not within father\'s available schedule');
        }

        // Add created_by
        $data = $input;
        $data['created_by'] = auth()->id();

        $etraf = $this->etrafRepository->store($data);
        // Assign user to father in father_users if first time
        $fatherUser = \App\Models\FatherUser::firstOrCreate([
            'father_id' => $input['father_id'],
            'user_id' => $data['user_id'] ?? auth()->id(),
        ]);

        return $etraf;
    }

    public function updateStatus($id, $status)
    {
        return $this->etrafRepository->updateStatus($id, $status);
    }

    public function getUserAtraf($user_id)
    {
        return $this->etrafRepository->getUserAtraf($user_id);
    }

    public function getCompletedAtrafByDate($date)
    {
        return $this->etrafRepository->getCompletedAtrafByDate($date);
    }
}
