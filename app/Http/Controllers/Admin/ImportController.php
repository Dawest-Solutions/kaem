<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\FileSave;
use App\Http\Traits\SearchSort;
use App\Imports\AchievementsImport;
use App\Imports\ObjectivesImport;
use App\Imports\OrdersImport;
use App\Models\Import;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends Controller
{

    public function __construct()
    {
        $this->middleware('adminAuth', ['except' => ['login', 'loginForm']]);
    }


    public function importObjectives()
    {
        return view('admin.imports.import_objectives');
    }


    public function importObjectivesFromXls(Request $request)
    {

        $file = $request->file('file');

        if (Excel::import(new ObjectivesImport, $file)) {
            Import::create([
                'name' => $file->getClientOriginalName(),
                'link' => FileSave::getUrl('public/imports', $file),
                'date' => $request->input('date'),
                'period_id' => $request->input('period_id'),
                'type' => 'objectives',
            ]);
        }

        return view('admin.imports.import_objectives')->with('success', collect(['Cele zostały pomyślnie zaktualizowane.']));
    }


    public function importAchievements()
    {
        return view('admin.imports.import_achievements');
    }


    public function importAchievementsFromXls(Request $request)
    {

        $file = $request->file('file');

        if (Excel::import(new AchievementsImport, $file)) {
            Import::create([
                'name' => $file->getClientOriginalName(),
                'link' => FileSave::getUrl('public/imports', $file),
                'date' => $request->input('date'),
                'period_id' => $request->input('period_id'),
                'type' => 'achievements',
            ]);
        }

        return view('admin.imports.import_achievements')->with('success', collect(['Realizacje zostały pomyślnie zaktualizowane.']));
    }


    public function importOrders()
    {
        return view('admin.imports.import_orders');
    }


    public function importOrdersFromXls(Request $request)
    {

        $file = $request->file('file');

        if (Excel::import(new OrdersImport, $file)) {
            Import::create([
                'name' => $file->getClientOriginalName(),
                'date' => $request->input('date'),
                'link' => FileSave::getUrl('public/imports', $file),
                'type' => 'orders',
            ]);
        }

        return view('admin.imports.import_orders')->with('success', collect(['Zamówienia zostały pomyślnie zaktualizowane.']));
    }

    public function importsHistory(Request $request)
    {
        $credits = SearchSort::credits($request);

        $imports = Import::whereHas('period', function ($query) use ($credits) {
            $query->where('name', 'like', '%' . $credits->get('search') . '%');
        })
            ->orWhere('date', 'like', '%' . $credits->get('search') . '%')
            ->orWhere('name', 'like', '%' . $credits->get('search') . '%')
            ->orderBy($credits->get('sort'), $credits->get('order'))
            ->paginate($credits->get('entries'));

        return view('admin.imports.imports_history', compact('imports'));
    }

    public function exportImportsHistory()
    {

    }
}
