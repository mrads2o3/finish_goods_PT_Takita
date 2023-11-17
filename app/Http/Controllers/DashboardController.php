<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Orders_items;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function dashboard(Request $get){

        $itemList = Items::query()
        ->leftjoin('transactions', 'items.id', '=', 'transactions.item_id')
        ->select('items.item_code', 'items.id as item_id', 'items.name', DB::raw("SUM(transactions.stock) as stock"))
        ->groupBy('items.name', 'items.id', 'items.item_code')
        ->orderBy('items.created_at', 'DESC')
        ->get();

        $getPendOrder = Orders_items::where('orders.status', 'sending')
        ->select('items_id', DB::raw("SUM(qty) as qty"))
        ->join('orders', 'orders_items.orders_id', '=', 'orders.id')
        ->groupBy('items_id')
        ->get();

        $i = 0;
        foreach($itemList as $var){
            $itemList[$i]->sending_qty = '-';
            $i++;
        }

        $i = 0;
        foreach($itemList as $var){
            foreach($getPendOrder as $var2){
                if($var->item_id == $var2->items_id){
                    $itemList[$i]->sending_qty = $var2->qty;
                    break;
                }else{
                    $itemList[$i]->sending_qty = '-';
                }
            }
            $i++;
        }

        $getCompOrder = Orders_items::where('orders.status', 'complete')
        ->select('items_id', DB::raw("SUM(qty) as qty"))
        ->join('orders', 'orders_items.orders_id', '=', 'orders.id')
        ->groupBy('items_id')
        ->get();

        $i = 0;
        foreach($itemList as $var){
            foreach($getCompOrder as $var2){
                if($var->item_id == $var2->items_id){
                    $itemList[$i]->stock = $var->stock - $var2->qty;
                }
            }
            $i++;
        }

        return view('index', ['location' => 'Dashboard', 'data' => $itemList]);
    }
}
