<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminOrderUpdateRequest;
use App\Http\Traits\SearchSort;
use App\Models\PrizeOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Request;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('adminAuth');
    }


    public function orders(Request $request)
    {
        $credits = SearchSort::credits($request);

        $orders = PrizeOrder::query()
            ->whereHas('prize', function ($query) use ($credits) {
                $query->where('model', 'like', '%' . $credits->get('search') . '%');
            })
            ->orWhereHas('pos', function ($query) use ($credits) {
                $query->where('number_pos', 'like', '%' . $credits->get('search') . '%');
            })
            ->orWhereHas('user', function ($query) use ($credits) {
                $query->where('first_name', 'like', '%' . $credits->get('search') . '%')
                    ->orWhere('last_name', 'like', '%' . $credits->get('search') . '%');
            })
            ->orWhereHas('status', function ($query) use ($credits) {
                $query->where('name', 'like', '%' . $credits->get('search') . '%');
            })
            ->orWhere('value', 'like', '%' . $credits->get('search') . '%')
            ->orderBy($credits->get('sort'), $credits->get('order'))
            ->paginate($credits->get('entries'));

        return view('admin.orders.index', compact('orders'));
    }

    public function orderInfo($id)
    {
        $info = PrizeOrder::find($id);
        $user = Auth::guard('admin')->user();
        $readonly = $user->hasRole('Admin') ? '' : 'readonly';

        return view('admin.orders.info', compact('info', 'readonly'));
    }

    public function orderUpdate(AdminOrderUpdateRequest $request, int $id)
    {

        if (PrizeOrder::find($id)->update($request->all())) {
            return Redirect::back()->with('success', collect(['Zamówienie zostało zaktualizowane.']));
        }

        return Redirect::back()->with('errors', collect(['Wystąpiły błędy podczas aktualizacji zamówienia.']));
    }

    public function exportOrders()
    {
        return Excel::download(new OrdersExport, 'strefahardy-orders.xlsx');
    }
}
