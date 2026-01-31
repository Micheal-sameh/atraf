<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEtrafRequest;
use App\Http\Requests\UpdateEtrafStatusRequest;
use App\Models\User;
use App\Services\EtrafService;
use App\Services\FatherScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mpdf\Mpdf;

/**
 * EtrafController
 *
 * Handles confession (etraf) management including creation, viewing,
 * status updates, and reporting functionalities.
 */
class EtrafController extends Controller
{
    public function __construct(
        protected EtrafService $etrafService,
        protected FatherScheduleService $fatherScheduleService
    ) {}

    /**
     * Display a listing of confessions with filters
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->search;
        $status = $request->status;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        // If user role (not father/admin), disable search
        $canSearch = $user->hasRole('father') || $user->hasRole('admin');
        if (! $canSearch) {
            $search = null;
        }

        $atraf = $this->etrafService->index($search, $status, $dateFrom, $dateTo);

        return view('atraf.index', compact('atraf', 'search', 'status', 'dateFrom', 'dateTo', 'canSearch'));
    }

    /**
     * Show the form for creating a new confession
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $fathers = User::role('father')->get();
        $users = User::all();
        $isAdmin = auth()->user()->hasRole('admin');

        return view('atraf.create', compact('fathers', 'users', 'isAdmin'));
    }

    /**
     * Get available time slots for a father on a specific date
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableSlots(Request $request)
    {
        $fatherId = $request->father_id;
        $date = $request->date;

        if (! $fatherId || ! $date) {
            return response()->json(['slots' => []]);
        }

        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));
        $schedules = $this->fatherScheduleService->getSchedulesByDay($fatherId, $dayOfWeek);

        $allSlots = [];
        foreach ($schedules as $schedule) {
            $slots = $schedule->getSlots();
            foreach ($slots as $slot) {
                // Check if slot is already taken
                $isTaken = \App\Models\Etraf::where('father_id', $fatherId)
                    ->whereDate('date', $date)
                    ->where('from', $slot['from'])
                    ->where('to', $slot['to'])
                    ->exists();

                if (! $isTaken) {
                    $allSlots[] = $slot;
                }
            }
        }

        return response()->json(['slots' => $allSlots]);
    }

    /**
     * Store a newly created confession
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEtrafRequest $request)
    {
        try {
            $this->etrafService->store($request->validated());

            return redirect()->route('atraf.index')->with('success', __('messages.success_created'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified confession
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $etraf = $this->etrafService->show($id);

        return view('atraf.show', compact('etraf'));
    }

    public function myEtraf()
    {
        $userId = auth()->id();
        $atraf = \App\Models\Etraf::where('user_id', $userId)
            ->with(['father', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        $upcoming = $atraf->filter(fn ($etraf) => $etraf->date >= now());
        $history = $atraf->filter(fn ($etraf) => $etraf->date < now());

        return view('atraf.my-etraf', compact('atraf', 'upcoming', 'history'));
    }

    public function updateStatus(UpdateEtrafStatusRequest $request, $id)
    {
        $etraf = $this->etrafService->updateStatus($id, $request->status);

        return redirect()->back()->with('success', __('messages.success_updated'));
    }

    public function userReport($user_id)
    {
        $user = User::findOrFail($user_id);
        $atraf = $this->etrafService->getUserAtraf($user_id);

        $html = view('atraf.user-report-pdf', compact('user', 'atraf'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);

        return $mpdf->Output('user_'.$user->name.'_report_'.date('Y-m-d').'.pdf', 'D');
    }

    public function fatherDailyReport($date)
    {
        $atraf = $this->etrafService->getCompletedAtrafByDate($date);

        // Group by father
        $fatherReports = $atraf->groupBy('father_id');

        foreach ($fatherReports as $father_id => $fatherAtraf) {
            $father = User::findOrFail($father_id);

            $html = view('atraf.father-daily-report-pdf', compact('father', 'fatherAtraf', 'date'))->render();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
            ]);

            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output('', 'S');

            // Send email
            Mail::send('emails.father-daily-report', compact('father', 'date'), function ($message) use ($father, $pdfContent, $date) {
                $message->to($father->email)
                    ->subject('Daily Report - '.$date)
                    ->attachData($pdfContent, 'report_'.$date.'.pdf');
            });
        }

        return redirect()->back()->with('success', 'Reports sent successfully');
    }
}
