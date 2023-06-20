<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function dashboard(Request $get){

        if($get->filled('date-min') && $get->filled('date-max')){
            $dmin = $get->query('date-min');
            $dmax = $get->query('date-max');
        }else if($get->filled('date-min')){
            $dmin = $get->query('date-min');
            $dmax = $get->query('date-min');
        }else if($get->filled('date-max')){
            $dmin = $get->query('date-max');
            $dmin = $get->query('date-max');
        }else{
            $dmin = 15000101;
            $dmax = 50501230;
        }

        $var = Items::query()
        ->join('transactions', 'items.id', '=', 'transactions.item_id')
        ->select('items.item_code', 'transactions.transaction_date', 'transactions.item_id', 'items.name', DB::raw("sum(transactions.jumlah) as jumlah"))
        ->groupBy('items.name', 'transactions.item_id', 'items.item_code', 'transactions.transaction_date')
        ->whereBetween('transaction_date', [$dmin, $dmax])
        ->get();

        return view('index', ['location' => 'Dashboard', 'data' => $var]);
    }
}
