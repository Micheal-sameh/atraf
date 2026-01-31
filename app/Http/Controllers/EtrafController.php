<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EtrafService;
use App\Services\FatherScheduleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EtrafController extends Controller
{
    public function __construct(
        protected EtrafService $etrafService,
        protected FatherScheduleService $fatherScheduleService
    ) {}

    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $atraf = $this->etrafService->index($search, $status);

        return view('atraf.index', compact('atraf', 'search', 'status'));
    }

    public function create()
    {
        $fathers = User::role('father')->get();
        $users = User::all();

        return view('atraf.create', compact('fathers', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'father_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->etrafService->store($request);

            return redirect()->route('atraf.index')->with('success', 'Etraf created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $etraf = $this->etrafService->show($id);

        return view('atraf.show', compact('etraf'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,waiting,completed',
        ]);

        $etraf = $this->etrafService->updateStatus($id, $request->status);

        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function userReport($user_id)
    {
        $user = User::findOrFail($user_id);
        $atraf = $this->etrafService->getUserAtraf($user_id);

        $pdf = PDF::loadView('atraf.user-report-pdf', compact('user', 'atraf'));
        $pdf->setPaper('a4');

        return $pdf->download('user_'.$user->name.'_report_'.date('Y-m-d').'.pdf');
    }

    public function fatherDailyReport($date)
    {
        $atraf = $this->etrafService->getCompletedAtrafByDate($date);

        // Group by father
        $fatherReports = $atraf->groupBy('father_id');

        foreach ($fatherReports as $father_id => $fatherAtraf) {
            $father = User::findOrFail($father_id);

            $pdf = PDF::loadView('atraf.father-daily-report-pdf', compact('father', 'fatherAtraf', 'date'));
            $pdf->setPaper('a4');

            // Send email
            Mail::send('emails.father-daily-report', compact('father', 'date'), function ($message) use ($father, $pdf, $date) {
                $message->to($father->email)
                    ->subject('Daily Report - '.$date)
                    ->attachData($pdf->output(), 'report_'.$date.'.pdf');
            });
        }

        return redirect()->back()->with('success', 'Reports sent successfully');
    }
}
