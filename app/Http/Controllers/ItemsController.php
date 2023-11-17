<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Exception;
use Illuminate\Http\Request;

class ItemsController extends Controller
{

    public function items(){
        $itemList = Items::orderBy('created_at', 'DESC')
        ->get();
        return view('items', ['location'=>'Item List', 'itemList'=>$itemList]);
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            Items::find($request->pk)
                ->update([
                    $request->name => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }

    public function delete($id){
        $item = Items::find($id);
        $item->delete();
        return response()->json(['success' => 'success']);
    }

    public function add(Request $req){
        try{
            $itemAdd = new Items();

            $itemAdd->item_code = $req->item_code;
            $itemAdd->name = $req->item_name;

            $itemAdd->save();

            return redirect()->route('items')->with('success', 'Item Added!');
        }catch (Exception $e){
            return redirect()->route('items')->with('error', $e->getMessage());
        }

    }
}
