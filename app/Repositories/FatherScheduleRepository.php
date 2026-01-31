<?php

namespace App\Repositories;

use App\Models\FatherSchedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FatherScheduleRepository extends BaseRepository
{
    public function __construct(FatherSchedule $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return FatherSchedule::class;
    }

    public bool $pagination = true;

    public int $perPage = 15;

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate($this->perPage) : $query->get();
    }

    public function index($father_id = null)
    {
        $query = $this->model->query()
            ->with('father:id,name,email')
            ->when(isset($father_id), fn ($q) => $q->where('father_id', $father_id))
            ->orderBy('day')
            ->orderBy('from');

        return $this->execute($query);
    }

    public function show($id)
    {
        return $this->findOrFail($id);
    }

    public function store($input)
    {
        return $this->model->create([
            'father_id' => $input->father_id,
            'day' => $input->day,
            'from' => $input->from,
            'to' => $input->to,
            'slot_duration' => $input->slot_duration ?? 15,
        ]);
    }

    public function delete($id)
    {
        $schedule = $this->findOrFail($id);
        $schedule->delete();
    }

    public function getSchedulesByDay($father_id, $day)
    {
        return $this->model->where('father_id', $father_id)
            ->where('day', $day)
            ->orderBy('from')
            ->get();
    }
}
