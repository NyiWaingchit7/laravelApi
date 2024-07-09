<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BrandController extends Controller
{
    public function index (){
        $brands = Brand::paginate(10);
         return response()->json($brands,200);
    }

    public function detail($id){
        $data = Brand::find($id);
        if(!$data) return response()->json(['message' => 'there is no brand']);
        return response()->json($data, 200);
    }


//create
    public function store(Request $request){
        try{
            $this->validation($request);
            $data = Brand::create(['name'=> $request->name]);
            return response()->json($data, 200);
        }catch(Exception $e){
            return response()->json($e,500);
        }
    }
//update
public function update_brand($id,Request $request){
    try{
        $exist = Brand::find($id);
        if(!$exist) return response()->json(['message' => 'there is no brand']);
        $this->validation($request);
        $exist->update($request->all());
        $data = $exist->refresh();
        return response()->json(['data'=>$data], 200);
    }catch(Exception $e){
        return response()->json($e,500);
    }
}
//delete
public function delete_brand($id){
    $exist = Brand::find($id);
    if(!$exist) return response()->json(['message' => 'there is no brand']);
    Brand::where('id',$id)->delete();
    return response()->json(['message'=>'deleted Successfully']);
}

    //validation

private function validation($request){

    Validator::make($request->all(),[
        'name'=>'required|unique:brands,name'
    ])->validate();
}
}


