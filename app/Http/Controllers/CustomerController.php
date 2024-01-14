<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customer()
    {
        $cust = Customer::get();
        return view('customers', ['location' => 'Customer List', 'customer' => $cust]);
    }

    public function add(Request $req)
    {
        try {

            $query = Customer::where('cust_name', $req->cust_name)->first();

            if($query){
                return back()->with('error', 'Customer name exist!');
            }

            $cust = new Customer;

            $cust->cust_name = $req->cust_name;

            $cust->save();

            return back()->with('success', 'Customer successfuly added!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            Customer::find($request->pk)
                ->update([
                    $request->name => $request->value
                ]);

            return response()->json(['success' => true]);
        }
    }

    public function delete($id)
    {
        $item = Customer::find($id);
        $item->delete();

        return response()->json(['success' => true]);
    }
}
