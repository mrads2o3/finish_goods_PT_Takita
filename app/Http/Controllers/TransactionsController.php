<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Items;
use Exception;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exact;

class TransactionsController extends Controller
{

    public function transaction()
    {
        $txs = Transactions::orderBy('created_at', 'DESC')
        ->get();
        $items = Items::orderBy('created_at', 'ASC')
        ->get();
        return view('transactions', ['location' => 'Transaction', 'transactions' => $txs, 'items' => $items]);
    }

    public function add(Request $req)
    {
        try {
            $query = Transactions::where('item_id', $req->item_id)
            ->where('transaction_date', $req->transaction_date)
            ->where('shift', $req->shift)
            ->get();

            if(count($query)){
                throw new Exception("Duplicate data not allowed!");
            }

            $tx = new Transactions;

            $tx->item_id = $req->item_id;
            $txQuery = Items::where('id', $req->item_id)->get();
            if(!count($txQuery)){
                throw new Exception("Can't get any item listed with id {$req->item_id}");
            }
            $tx->item_name = $txQuery[0]->name;
            $tx->transaction_date = $req->transaction_date;
            $tx->shift = $req->shift;
            $tx->stock = $req->stock;

            $tx->save();

            return redirect()->route('transactions')->with('success', 'Transaction Added!');
        } catch (Exception $e) {
            return redirect()->route('transactions')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            Transactions::find($request->pk)
                ->update([
                    $request->name => $request->value
                ]);

            return response()->json(['success' => true]);
        }
    }

    public function delete($id)
    {
        $tx = Transactions::find($id);
        $tx->delete();

        return response()->json(['success' => true]);
    }
}
