<?php

namespace App\Http\Controllers;

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
        $father_id = $request->father_id ?? auth()->id();
        $schedules = $this->fatherScheduleService->index($father_id);
        $fathers = User::role('father')->get();

        return view('father_schedules.index', compact('schedules', 'fathers', 'father_id'));
    }

    public function create()
    {
        $fathers = User::role('father')->get();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('father_schedules.create', compact('fathers', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'father_id' => 'required|exists:users,id',
            'day' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from',
            'slot_duration' => 'nullable|integer|min:5|max:120',
        ]);

        $this->fatherScheduleService->store($request);

        return redirect()->route('father-schedules.index')->with('success', 'Schedule created successfully');
    }

    public function destroy($id)
    {
        $this->fatherScheduleService->delete($id);

        return redirect()->route('father-schedules.index')->with('success', 'Schedule deleted successfully');
    }
}
