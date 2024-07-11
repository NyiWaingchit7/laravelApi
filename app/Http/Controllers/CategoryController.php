<?php

namespace App\Http\Controllers;
use Storage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function index(){
        $data = Category::paginate(10);
        return response()->json($data, 200);
    }

    //id data
    public function detail($id){
        $data = Category::find($id);
        if(!$data) return response()->json(["message" => "no category with this id"]);
        return response()->json($data, 200);
    }
    //create
    public function store(Request $request){
      try{
        $this->validation($request);

        $image;

        if($request->hasFile('image')){
            $fileName= uniqid() . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/',$fileName);
            $image = $fileName;
        }
        $data = Category::create(['name'=>$request->name,'image'=>$image]);

        return response()->json($data, 200);
      }catch(Exception $e){
        return response()->json($e, 200);
      }
    }

    //update
    public function update_category(Request $request,$id){
        try{
            $exist = Category::find($id);
            if(!$exist) return response()->json(['message' => 'there is no category']);
            $this->validation($request);
            $image;


            if($request->hasFile('image')){

                $oldPic = Category::where('id',$id)->first();
                $oldPic = $oldPic->image;
                Storage::delete('public/'.$oldPic);
                $fileName= uniqid() . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/',$fileName);
                $image = $fileName;
                $exist->image=$image;

            }

            $exist->name = $request->name;
            $exist->save();
            return response()->json(['data'=>$exist], 200);
        }catch(Exception $e){
            return response()->json($e,500);
        }
    }

    //delete
    public function delete_category($id){
        $exist = Category::find($id);
        if(!$exist) return response()->json(['message' => 'there is no brand']);
        Category::where('id',$id)->delete();
        return response()->json(['message'=>'deleted Successfully']);
    }






        //validation

    private function validation($request){

          Validator::make($request->all(),[
             'name'=>'required|unique:brands,name'
         ])->validate();
     }
}
