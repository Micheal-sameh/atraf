<?php

namespace App\Repositories;

use App\Models\Etraf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EtrafRepository extends BaseRepository
{
    public function __construct(Etraf $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return Etraf::class;
    }

    public bool $pagination = true;

    public int $perPage = 15;

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate($this->perPage) : $query->get();
    }

    public function index($search = null, $status = null)
    {
        $query = $this->model->query()
            ->with(['father:id,name,email', 'user:id,name,email'])
            ->when(isset($status), fn ($q) => $q->where('status', $status))
            ->when(isset($search), fn ($q) => $q->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            })->orWhereHas('father', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            }))
            ->orderByDesc('date')
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
            'user_id' => $input->user_id,
            'date' => $input->date,
            'from' => $input->from,
            'to' => $input->to,
            'notes' => $input->notes ?? null,
        ]);
    }

    public function updateStatus($id, $status)
    {
        $etraf = $this->findOrFail($id);
        $etraf->update(['status' => $status]);

        return $etraf;
    }

    public function getUserAtraf($user_id)
    {
        $this->pagination = false;

        return $this->model->where('user_id', $user_id)
            ->with(['father:id,name'])
            ->orderByDesc('date')
            ->get();
    }

    public function getCompletedAtrafByDate($date)
    {
        return $this->model->where('date', $date)
            ->where('status', 'completed')
            ->with(['father:id,name,email', 'user:id,name,email'])
            ->get();
    }
}
