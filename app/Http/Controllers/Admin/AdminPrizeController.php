<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PrizesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPrizeCreateRequest;
use App\Http\Requests\Admin\AdminPrizeUpdateRequest;
use App\Http\Traits\FileSave;
use App\Http\Traits\SearchSort;
use App\Models\Prize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminPrizeController extends Controller
{

    public function __construct()
    {
        $this->middleware('adminAuth', ['except' => ['login', 'loginForm']]);
    }


    public function prizes(Request $request)
    {
        $credits = SearchSort::credits($request);
        $user = Auth::guard('admin')->user();
        $readonly = $user->hasRole('Admin') ? '' : 'readonly';

        $prizes = Prize::whereHas('category', function($query) use ($credits) {
            $query->where('name', 'like', '%' . $credits->get('search') . '%');
        })->orWhere('name', 'like', '%' . $credits->get('search') . '%')
            ->orderBy($credits->get('sort'), $credits->get('order'))
            ->paginate($credits->get('entries'));

        return view('admin.prizes.index', compact('prizes', 'readonly'));
    }


    public function prizeNew()
    {
        return view('admin.prizes.new');
    }


    public function prizeCreate(AdminPrizeCreateRequest $request)
    {

        // check if photo exists
        if ($request->file('file') !== null) {
            $request->merge([
                'photo' => FileSave::getUrl('public/prizes',$request->file('file')),
            ]);
        }

        if (Prize::create($request->all())) {
            return $this->prizes($request)->with('success', collect(['Utworzono nową nagrodę.']));
        }

        return $this->prizes($request)->with('errors', collect(['Podczas tworzenia nowej nagrody wystąpiły błędy.']));
    }


    public function prizeInfo($id)
    {

        $info = Prize::find($id);
        $user = Auth::guard('admin')->user();
        $readonly = $user->hasRole('Admin') ? '' : 'readonly';

        return view('admin.prizes.info', compact('info', 'readonly'));
    }


    public function prizeUpdate(AdminPrizeUpdateRequest $request, int $id)
    {

        // check if photo exists
        if ($request->file('file') !== null) {
            $request->merge([
                'photo' => FileSave::getUrl('public/prizes',$request->file('file')),
            ]);
        }

        if (Prize::find($id)->update($request->all())) {
            return Redirect::back()->with('success', collect(['Nagroda została zaktualizowana.']));
        }

        return Redirect::back()->with('errors', collect(['Wystąpiły błędy podczas aktualizacji nagrody.']));
    }


    public function exportPrizes(): BinaryFileResponse
    {
        return Excel::download(new PrizesExport(), 'strefahardy-prizes.xlsx');
    }

}
