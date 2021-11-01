<?php

namespace App\Http\Controllers\Admin;

use App\Exports\POSExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPOSCreateRequest;
use App\Http\Requests\Admin\AdminPOSUpdateRequest;
use App\Http\Traits\SearchSort;
use App\Models\Ph;
use App\Models\Pos;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminPOSController extends Controller
{
    public function __construct()
    {
        $this->middleware('adminAuth', ['except' => ['login', 'loginForm']]);
    }


    public function pos(Request $request)
    {
        $credits = SearchSort::credits($request);
        $user = Auth::guard('admin')->user();
        $readonly = $user->hasRole('Admin') ? '' : 'readonly';

        if ($user->hasRole('Admin|Central')) {
            $pos = $this->getPos(Pos::query(), $credits);
        }

        if ($user->hasRole('RKS')) {
            $id = $user->rks->first()->id;

            $phs = Ph::where('rks_id', $id)
                ->get()
                ->pluck('id');

            $cond = Pos::whereIn('ph_id', $phs);

            $pos = $this->getPos($cond, $credits);
        }

        if ($user->hasRole('PH')) {
            // check if PH has no POS-es
            if (is_null($user->phs->first())) {
                return \redirect()->back()->with('errors', collect(['Brak przypisanych POS-ów']));
            }

            $id = $user->phs->first()->id;

            $cond = Pos::where('ph_id', $id);
            $pos = $this->getPos($cond, $credits);
        }

        return view('admin.pos.index', compact('pos', 'readonly'));
    }


    public function posNew()
    {
        return view('admin.pos.new');
    }


    public function posCreate(AdminPOSCreateRequest $request)
    {

        $data = $request->filter();
        $data['password'] = bcrypt($data['password']);

        if (Pos::create($data)) {
            return $this->pos($request)->with('success', collect(['Utworzono nowy pojedynczy sklep.']));
        }

        return $this->pos($request)->with('errors', collect(['Podczas tworzenia nowego sklepu wystąpiły błędy.']));
    }


    public function posInfo($id)
    {
        $user = Auth::guard('admin')->user();
        $info = Pos::find($id);
        $readonly = $user->hasRole('Admin') ? '' : 'readonly';

        return view('admin.pos.info', compact('info', 'readonly'));
    }


    public function posUpdate(AdminPOSUpdateRequest $request, int $id)
    {

        $data = $request->filter();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        if (Pos::find($id)->update($data)) {
            return Redirect::back()->with('success', collect(['Pojedynczy sklep został zaktualizowany.']));
        }

        return Redirect::back()->with('errors', collect(['Wystąpiły błędy podczas aktualizacji sklepu.']));
    }


    public function exportPOS(): BinaryFileResponse
    {
        return Excel::download(new POSExport(), 'strefahardy-pos.xlsx');
    }


    /**
     * @param $pos
     * @param Collection $credits
     * @return mixed
     */
    private function getPos($pos, Collection $credits)
    {
        return $pos->whereHas('ph', function ($query) use ($credits) {
                $query->where('name', 'like', '%' . $credits->get('search') . '%');
            })
            ->orWhere('user_id', 'like', '%' . $credits->get('search') . '%')
            ->orWhere('ph_id', 'like', '%' . $credits->get('search') . '%')
            ->orWhere('number_pos_main', 'like', '%' . $credits->get('search') . '%')
            ->orWhere('number_pos', 'like', '%' . $credits->get('search') . '%')
            ->orWhere('company_name', 'like', '%' . $credits->get('search') . '%')
            ->orderBy($credits->get('sort'), $credits->get('order'))
            ->paginate($credits->get('entries'));
    }

}
