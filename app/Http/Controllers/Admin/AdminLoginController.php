<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class AdminLoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('adminAuth', ['except' => ['login', 'loginForm']]);
    }

    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(AdminLoginRequest $request)
    {
        if (Auth::guard('admin')->attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ])) {

            return redirect('admin')->with('success', new MessageBag(['login' => 'Logowanie wykonane poprawnie']));
        }

        return redirect()->back()->withErrors(['login' => 'Nieprawidłowe dane do logowania']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect('admin/login');
    }

    public function dashboard()
    {
        return view('admin.dashboard.index');
    }

}
