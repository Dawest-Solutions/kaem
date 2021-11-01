<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminCreateRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Http\Requests\Admin\AdminUserCreateRequest;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use App\Http\Traits\SearchSort;
use App\Models\Admin;
use App\Models\Ph;
use App\Models\Pos;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('adminAuth', ['except' => ['login', 'loginForm']]);
    }


    public function users(Request $request)
    {
        $credits = SearchSort::credits($request);
        $user = Auth::guard('admin')->user();
        $readonly = $user->hasRole('Admin') ? '' : 'readonly';

        if ($user->hasRole('Admin|Central')) {
            $users = $this->getUsers(User::query(), $credits);
        }

        if ($user->hasRole('RKS')) {
            $id = $user->rks->first()->id;

            $phs = Ph::where('rks_id', $id)
                ->get()
                ->pluck('id');

            $poses = Pos::whereIn('ph_id', $phs)
                ->get()
                ->pluck('id');

            $users = $this->getUsers(User::whereIn('pos_id', $poses), $credits);
        }

        if ($user->hasRole('PH')) {

            // check if PH has no users
            if (is_null($user->phs->first())) {
                return \redirect()->back()->with('errors', collect(['Brak przypisanych uczestników']));
            }

            $id = $user->phs->first()->id;

            $poses = Pos::where('ph_id', $id)
                ->get()
                ->pluck('id');

            $users = $this->getUsers(User::whereIn('pos_id', $poses), $credits);
        }

        return view('admin.users.index', compact('users', 'readonly'));
    }

    public function userNew()
    {
        return view('admin.users.new');
    }

    public function userCreate(AdminUserCreateRequest $request)
    {

        $data = $request->filter();
        $data['password'] = bcrypt($data['password']);

        if (User::create($data)) {
            return $this->users($request)->with('success', collect(['Utworzono nowego użytkownika.']));
        }

        return $this->users($request)->with('errors', collect(['Podczas tworzenia nowego użytkownika wystąpiły błędy.']));
    }

    public function userInfo($id)
    {

        $info = User::find($id);
        $user = Auth::guard('admin')->user();
        $readonly = $user->hasRole('Admin') ? '' : 'readonly';

        return view('admin.users.info', compact('info', 'readonly'));
    }

    public function userUpdate(AdminUserUpdateRequest $request, int $id)
    {

        $data = $request->filter();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        if (User::find($id)->update($data)) {
            return Redirect::back()->with('success', collect(['Użytkownik został zaktualizowany.']));
        }

        return Redirect::back()->with('errors', collect(['Wystąpiły błędy podczas aktualizacji użytkownika.']));
    }

    public function exportUsers(): BinaryFileResponse
    {
        return Excel::download(new UsersExport, 'strefahardy-users.xlsx');
    }

// --------------------------------------------------------------------------------

    public function admins(Request $request)
    {

        $admins = Admin::all();

        return view('admin.admins.index', compact('admins'));
    }

    public function adminNew()
    {
        return view('admin.admins.new');
    }

    public function adminCreate(AdminCreateRequest $request)
    {

        $data = $request->filter();
        $data['password'] = bcrypt($data['password']);

        if (Admin::create($data)) {
            return $this->admins($request)->with('success', collect(['Utworzono nowego administratora.']));
        }

        return $this->admins($request)->with('errors', collect(['Podczas tworzenia nowego administratora wystąpiły błędy.']));
    }

    public function adminInfo($id)
    {

        $info = Admin::find($id);

        return view('admin.admins.info', compact('info'));
    }

    public function adminUpdate(AdminUpdateRequest $request, int $id)
    {

        $data = $request->filter();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        if (Admin::find($id)->update($data)) {
            return Redirect::back()->with('success', collect(['Administrator został zaktualizowany.']));
        }

        return Redirect::back()->with('errors', collect(['Wystąpiły błędy podczas aktualizacji administratora.']));
    }

    public function loginOnUserAccount(User $user)
    {
        Auth::guard('web')->login($user);
        $token = Auth::user()->currentAccessToken();
        if (is_null($token )){
            $token = $user->createToken('ADMIN TOKEN API')->plainTextToken;
        }
        return redirect()->route('loginAs', $token);
    }

    /**
     * @param $users
     * @param Collection $credits
     * @return mixed
     */
    private function getUsers($users, Collection $credits)
    {

        return $users->whereHas('pos', function ($query) use ($credits) {
                $query->where('number_pos_main', 'like', '%' . $credits->get('search') . '%')
                    ->orWhere('number_pos', 'like', '%' . $credits->get('search') . '%')
                    ->orWhere('company_name', 'like', '%' . $credits->get('search') . '%');
            })
                ->orWhere('first_name', 'like', '%' . $credits->get('search') . '%')
                ->orWhere('last_name', 'like', '%' . $credits->get('search') . '%')
                ->orWhere('phone', 'like', '%' . $credits->get('search') . '%')
                ->orWhere('email', 'like', '%' . $credits->get('search') . '%')
                ->orderBy($credits->get('sort'), $credits->get('order'))
                ->paginate($credits->get('entries'));
    }
}
