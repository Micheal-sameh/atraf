<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFatherScheduleRequest;
use App\Models\User;
use App\Services\FatherScheduleService;
use Illuminate\Http\Request;

class FatherScheduleController extends Controller
{
    public function __construct(
        protected FatherScheduleService $fatherScheduleService
    ) {}

    public function index(Request $request)
    {
        // If user is father, filter by their ID by default
        $father_id = $request->father_id;
        if (! $father_id && auth()->user()->hasRole('father')) {
            $father_id = auth()->id();
        }

        $schedules = $this->fatherScheduleService->index($father_id);
        $fathers = User::role('father')->get();

        return view('father_schedules.index', compact('schedules', 'fathers', 'father_id'));
    }

    public function create()
    {
        $user = auth()->user();

        // If user is father, only show themselves in the list
        if ($user->hasRole('father')) {
            $fathers = User::where('id', $user->id)->get();
        } else {
            $fathers = User::role('father')->get();
        }

        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('father_schedules.create', compact('fathers', 'days'));
    }

    public function store(StoreFatherScheduleRequest $request)
    {
        $this->fatherScheduleService->store($request);

        return redirect()->route('father-schedules.index')->with('success', __('messages.success_created'));
    }

    public function destroy($id)
    {
        $this->fatherScheduleService->delete($id);

        return redirect()->route('father-schedules.index')->with('success', __('messages.success_deleted'));
    }
}
