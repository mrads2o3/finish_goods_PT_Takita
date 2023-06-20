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
            $cust = new Customer;

            $cust->cust_name = $req->cust_name;

            $cust->save();

            return redirect()->route('customers')->with('success', 'Customer Added!');
        } catch (Exception $e) {
            return redirect()->route('customers')->with('error', 'Error while input data, please check your entry!');
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
