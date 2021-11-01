<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Request;

class AdminPeriodsController extends Controller
{

    public function __construct()
    {
        $this->middleware('adminAuth', ['except' => ['login', 'loginForm']]);
    }


    public function periods()
    {
        $periods = Period::all();

        return view('admin.periods.index', compact('periods'));
    }


    public function periodsUpdate(Request $request)
    {
        Schema::disableForeignKeyConstraints();
        Period::truncate();
        Schema::enableForeignKeyConstraints();

        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            Period::create([
                'name' => $value,
                'number' => $key,
            ]);
        }

        return Redirect::back()->with('success', collect(['Okresy sprzedaży zostały pomyślnie zaktualizowane.']));
    }

    public function changeDefault(Request $request)
    {
        session()->put('period_id', $request->input('period_id'));
        return redirect()->back();
    }

}
