<?php

namespace App\Http\Controllers;

use App\Models\Etraf;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $families = [];

        if (! $search) {
            return view('families.index', compact('families', 'search'));
        }

        // Extract family codes using a single optimized query
        $familyCodes = User::where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('membership_code', 'like', "%{$search}%");
        })
            ->get(['membership_code'])
            ->map(function ($user) {
                if (preg_match('/^(E\d+C\d+F\d+)/', $user->membership_code, $matches)) {
                    return $matches[1];
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values();

        if ($familyCodes->isEmpty()) {
            return view('families.index', compact('families', 'search'));
        }

        // Get all family members in a single query
        $allMembers = User::where(function ($query) use ($familyCodes) {
            foreach ($familyCodes as $code) {
                $query->orWhere('membership_code', 'like', $code.'%');
            }
        })
            ->orderBy('membership_code')
            ->get(['id', 'name', 'membership_code']);

        // Group members by family code
        $families = $allMembers->groupBy(function ($user) {
            if (preg_match('/^(E\d+C\d+F\d+)/', $user->membership_code, $matches)) {
                return $matches[1];
            }

            return null;
        })
            ->filter()
            ->map(fn ($members, $code) => [
                'code' => $code,
                'members' => $members,
            ])
            ->values()
            ->all();

        return view('families.index', compact('families', 'search'));
    }

    public function show($familyCode)
    {
        // Get all family members
        $members = User::where('membership_code', 'like', $familyCode.'%')
            ->orderBy('membership_code')
            ->get(['id', 'name', 'membership_code', 'email']);

        if ($members->isEmpty()) {
            return view('families.show', [
                'membersData' => [],
                'familyCode' => $familyCode,
            ]);
        }

        $memberIds = $members->pluck('id')->all();

        // Get atraf statistics for each member
        $atrafStats = Etraf::whereIn('user_id', $memberIds)
            ->select('user_id',
                DB::raw('COUNT(*) as total_atraf'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_atraf'),
                DB::raw('MAX(created_at) as last_etraf_date'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        // Build member data
        $membersData = $members->map(function ($member) use ($atrafStats) {
            $stats = $atrafStats[$member->id] ?? null;

            return [
                'user' => $member,
                'total_atraf' => $stats->total_atraf ?? 0,
                'completed_atraf' => $stats->completed_atraf ?? 0,
                'last_etraf_date' => $stats->last_etraf_date ?? null,
            ];
        })->all();

        return view('families.show', compact('membersData', 'familyCode'));
    }

    public function export($familyCode)
    {
        // Get all family members
        $members = User::where('membership_code', 'like', $familyCode.'%')
            ->orderBy('membership_code')
            ->get(['id', 'name', 'membership_code', 'email']);

        if ($members->isEmpty()) {
            return redirect()->back()->with('error', 'No family members found');
        }

        $memberIds = $members->pluck('id')->all();

        // Get atraf statistics for each member
        $atrafStats = Etraf::whereIn('user_id', $memberIds)
            ->select('user_id',
                DB::raw('COUNT(*) as total_atraf'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_atraf'),
                DB::raw('MAX(created_at) as last_etraf_date'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        // Build member data
        $membersData = $members->map(function ($member) use ($atrafStats) {
            $stats = $atrafStats[$member->id] ?? null;

            return [
                'user' => $member,
                'total_atraf' => $stats->total_atraf ?? 0,
                'completed_atraf' => $stats->completed_atraf ?? 0,
                'last_etraf_date' => $stats->last_etraf_date ?? null,
            ];
        })->all();

        $pdf = PDF::loadView('families.export-pdf', compact('membersData', 'familyCode'));
        $pdf->setPaper('a4');

        return $pdf->download('family_'.$familyCode.'_'.date('Y-m-d').'.pdf');
    }
}
