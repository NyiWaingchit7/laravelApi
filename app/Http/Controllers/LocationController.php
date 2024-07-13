<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    //create
    public function store(Request $request){
try{
    $this->validation($request);
    $data = Location::create([
        'area'=> $request->area,
        'street'=>$request->street,
        'building'=>$request->building,
        'user_id'=>Auth::id()
    ]);
    return response()->json($data, 200);
        }catch(Exception $e){
            return response()->json($e);
        }
    }

    //update
    public function update_location(Request $request,$id){
        try{
            $exist = Location::find($id);
            if(!$exist) return response()->json(['message'=>'no location found']);
            $this->validation($request);
            $exist->area = $request->area;
            $exist->street = $request->street;
            $exist->building = $request->building;
            $exist->save();
            return response()->json($exist, 200);
        }catch(Exception $e){
            return response()->json($e);
        }
    }

    //delete
    public function delete_location($id){
        try{
            $exist = Location::find($id);
            if(!$exist) return response()->json(['message'=>'no location found']);
            $exist->delete();
            return response()->json(['mesage'=>'deleted successfully']);
        }catch(Exception $e){
            return response()->json($e);
        }

    }


    //validation
    private function validation($request){
        Validator::make($request->all(),
        [
            'area' => "required",
            'street' => 'required',
            'building'=>'required',

        ])->validate();
    }
}
