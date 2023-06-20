<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Http\Requests\StoreTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Models\Items;
use Exception;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function transaction()
    {
        $txs = Transactions::get();
        $items = Items::get();
        return view('transactions', ['location' => 'Transaction', 'transactions' => $txs, 'items' => $items]);
    }

    public function add(Request $req)
    {
        try {
            $tx = new Transactions;

            $tx->item_id = $req->item_id;
            $txQuery = Items::find($req->item_id);
            $tx->item_name = $txQuery->name;
            $tx->transaction_date = $req->transaction_date;
            $tx->shift = $req->shift;
            $tx->jumlah = $req->jumlah;

            $tx->save();

            return redirect()->route('transactions')->with('success', 'Customer Added!');
        } catch (Exception $e) {
            return redirect()->route('transactions')->with('error', 'Error while input data, please check your entry!');
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
