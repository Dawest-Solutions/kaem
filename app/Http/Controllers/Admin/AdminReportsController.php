<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportPHReport;
use App\Exports\ExportPOSReport;
use App\Exports\ExportRKSReport;
use App\Http\Controllers\Controller;
use App\Http\Traits\SearchSort;
use App\Models\Period;
use App\Models\Ph;
use App\Models\Pos;
use App\Models\Rks;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Request;

class AdminReportsController extends Controller
{

    public function __construct()
    {
        $this->middleware('adminAuth', ['except' => ['login', 'loginForm']]);
    }


    public function reportRKS(Request $request)
    {

        $rks = Rks::all();

        return view('admin.reports.rks', compact('rks'));
    }


    public function reportPH(Request $request)
    {

        $user = Auth::guard('admin')->user();

        if ($user->hasRole('Admin|Central')) {
            $ph = isset($request->id) ? Ph::where('rks_id', $request->id)->get() : Ph::all();
        }

        if ($user->hasRole('RKS')) {
            $ph = Ph::where('rks_id', $user->rks->first()->id)->get();
        }

        return view('admin.reports.ph', compact('ph'));
    }


    public function reportPOS(Request $request)
    {

        $user = Auth::guard('admin')->user();
        $credits = SearchSort::credits($request);

        if ($user->hasRole('Admin|Central')) {
            $condition = isset($request->id)
                ? Pos::where('ph_id', $request->id)
                : Pos::query();
        }

        if ($user->hasRole('RKS')) {
            $phs = Ph::where('rks_id', $user->rks->first()->id)->get()->pluck('id');
            $condition = isset($request->id)
                ? Pos::whereIn('ph_id', $phs)->where('ph_id', $request->id)
                : Pos::whereIn('ph_id', $phs);
        }

        if ($user->hasRole('PH')) {
            // check if PH has no POS-es
            if (is_null($user->phs->first())) {
                return \redirect()->back()->with('errors', collect(['Brak przypisanych POS-ów']));
            }

            $condition = Pos::where('ph_id', $user->phs->first()->id);
        }

        $pos = $this->getPos($condition, $credits);

        return view('admin.reports.pos', compact('pos'));
    }


    public function exportReportRKS()
    {
        return Excel::download(new ExportRKSReport, 'RKS-report.xlsx');
    }


    public function exportReportPH()
    {
        return Excel::download(new ExportPHReport, 'PH-report.xlsx');
    }


    public function exportReportPOS()
    {
        return Excel::download(new ExportPOSReport, 'POS-report.xlsx');
    }

    /**
     * @param $pos
     * @param Collection $credits
     * @return mixed
     */
    private function getPos($pos, Collection $credits)
    {
        return $pos->whereHas('ph', function($query) use ($credits) {
                $query->where('name', 'like', '%' . $credits->get('search') . '%');
            })
            ->orWhere('number_pos_main', 'like', '%' . $credits->get('search') . '%')
            ->orWhere('number_pos', 'like', '%' . $credits->get('search') . '%')
            ->orWhere('company_name', 'like', '%' . $credits->get('search') . '%')
            ->orderBy($credits->get('sort'), $credits->get('order'))
            ->paginate($credits->get('entries'));
    }
}
