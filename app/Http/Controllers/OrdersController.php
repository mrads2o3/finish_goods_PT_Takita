<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\Customer;
use App\Models\Items;
use App\Models\Orders_items;
use App\Models\Transactions;
use Exception;
use Illuminate\Http\Request;
use LengthException;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class OrdersController extends Controller
{
    public function orders()
    {
        $order = orders::query()
            ->join('customers', 'customers.id', '=', 'orders.cust_id')
            ->select('orders.id', 'orders.order_id','customers.cust_name', 'orders.request_date', 'orders.send_date', 'status', 'orders.updated_at', 'orders.sending_at', 'orders.cancel_at', 'orders.complete_at')
            ->get();

        $cust = Customer::get();
        // dd($order);
        return view('orders', ['location' => 'Orders', 'orders' => $order, 'cust' => $cust]);
    }

    public function ordersDetails($id)
    {
        $order = orders::query()
            ->join('customers', 'customers.id', '=', 'orders.cust_id')
            ->select('orders.id', 'orders.order_id', 'customers.cust_name', 'orders.request_date', 'orders.send_date', 'status', 'orders.updated_at', 'orders.sending_at', 'orders.cancel_at', 'orders.complete_at')
            ->where('orders.id', '=', $id)
            ->get();

        $items = Orders_items::query()
            ->join('items', 'orders_items.items_id', '=', 'items.id')
            ->select('orders_items.id as orders_items_id','orders_items.qty', 'orders_items.price', 'items.name')
            ->where('orders_id', '=', $id)
            ->get();

        $itemsList = Items::get();
        return view('ordersdetails', ['location' => 'Orders', 'orders' => $order, 'items' => $items, 'items_list' => $itemsList]);
    }

    public function orderAdd(Request $req)
    {
        try {
            if($req->request_date > $req->send_date){
                throw new Exception("Request date can't be more than Send date!");
            }

            $query = Customer::where('id', $req->cust_id)->get();

            if(!count($query)){
                throw new Exception("Can't get any customer listed with id {$req->cust_id}");
            }

            $order = new orders();
            $query = orders::orderBy('id', 'desc')->first();
            if($query){
                $id = ($query->id) + 1;
            }else{
                $id = 1;
            }
            $order->order_id = 'CS' . str_pad($id, 4, '0', STR_PAD_LEFT);
            $order->cust_id = $req->cust_id;
            $order->request_date = $req->request_date;
            $order->send_date = $req->send_date;
            $order->status = 'pending';

            $order->save();

            return back()->with('success', 'Order created!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function orderCancel($id)
    {
        try {
            $order = orders::where('id', $id)
                ->update([
                    'status' => 'cancel',
                    'cancel_at' => now()
                ]);

            return back()->with('success', 'Order canceled!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function orderComplete($id)
    {
        try {
            $order = orders::where('id', $id)
                ->update([
                    'status' => 'complete',
                    'complete_at' => now()
                ]);

            return back()->with('success', 'Order Complete');
        } catch (Exception $e) {
            return back()->with('failed', $e->getMessage());
        }
    }

    public function orderSending($id)
    {

        try {

            $getStock = Items::query()
            ->leftjoin('transactions', 'items.id', '=', 'transactions.item_id')
            ->select('items.item_code', 'items.id as item_id', 'items.name', DB::raw("SUM(transactions.stock) as stock"))
            ->groupBy('items.name', 'items.id', 'items.item_code')
            ->get();
    
            $getOrder = Orders_items::where('orders.id', $id)
            ->select('items_id', DB::raw("SUM(qty) as qty"))
            ->join('orders', 'orders_items.orders_id', '=', 'orders.id')
            ->groupBy('items_id')
            ->get();

            if(!count($getOrder)){
                throw new Exception("Please add at least 1 item on order!");
            }

            $getCompOrder = Orders_items::where('orders.status', 'complete')
            ->select('items_id', DB::raw("SUM(qty) as qty"))
            ->join('orders', 'orders_items.orders_id', '=', 'orders.id')
            ->groupBy('items_id')
            ->get();

            $i = 0;
            foreach($getStock as $var){
                foreach($getCompOrder as $var2){
                    if($var->item_id == $var2->items_id){
                        $getStock[$i]->stock = $var->stock - $var2->qty;
                    }
                }
                $i++;
            }
    
            // Check avail stock
            foreach($getOrder as $val){
                foreach($getStock as $val2){
                    if($val->items_id == $val2->item_id){
                        if(is_null($val2->stock)){
                            throw new Exception('Item "'.$val2->name.'" has no stock!');
                        }
                        if($val->qty > $val2->stock){
                            throw new Exception('Stock from item : "'.$val2->name.'" not enough! Stock : '.$val2->stock.', Qty required : '.$val->qty);
                        }
                    }
                }
            }

            $order = orders::where('id', $id)
                ->update([
                    'status' => 'sending',
                    'sending_at' => now()
                ]);

            return back()->with('success', 'Order update to Sending!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        
    }

    public function ordersAddItem(Request $req)
    {
        try {

            $checkItem = Orders_items::where('orders_id', $req->order_id)
                ->where('items_id', $req->items_id)
                ->where('price', $req->price)
                ->get();

            if (count($checkItem)) {
                $order = Orders_items::where('orders_id', $req->order_id)
                    ->where('items_id', $req->items_id)
                    ->where('price', $req->price)
                    ->update([
                        'qty' => $checkItem[0]['qty'] + $req->qty,
                    ]);

                return back()->with('success', 'Item Updated!');
            }

            $order = new Orders_items();
            $order->orders_id = $req->order_id;
            $order->items_id = $req->items_id;
            $order->price = $req->price;
            $order->qty = $req->qty;
            $order->save();

            return back()->with('success', 'Item Added!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function ordersDelItem($id) {
        try{
            $query = Orders_items::find($id);
            $query->delete();
            return back()->with('success', 'Item Deleted!');
        }catch (Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
}
