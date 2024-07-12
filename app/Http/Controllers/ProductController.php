<?php

namespace App\Http\Controllers;
use Storage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //all
    public function index(){
        $data = Product::paginate(10);
        if(!$data) return response()->json(['message'=>'no product found']);
        return response()->json($data, 200);
    }
    //show
    public function detail($id){
        $data = Product::find($id);
        if(!$data) return response()->json(['message'=>'no product found']);
        return response()->json($data, 200);
    }
    //create
    public function store(Request $request){
        try{
            $this->validation($request);
            $data = $this->getData($request);
            if($request->hasFile('image')){
                $fileName = uniqid() . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/' . $fileName);
                $data['image'] = $fileName;
            }
            $products = Product::create($data);
            return response()->json($products, 200);


        }catch(Exception $e){
            return response()->json($e);
        }
    }
    public function update_product(Request $request,$id){
        try{
            $exist = Product::find($id);
            if(!$exist) return response()->json(['message'=> 'no product found']);
            $this->validation($request);
            $data = $this->getData($request);
            if($request->hasFile('image')){
                $oldpic =Product::where('id',$id)->first();
                $oldpic = $oldpic->image;
                Storage::delete('public/'.$oldpic);
                $fileName = uniqid() . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/' . $fileName);
                $data['image'] = $fileName;
            }
            $products = Product::where('id',$id)->update($data);
            $products = $exist->refresh();
            return response()->json($products, 200);


        }catch(Exception $e){
            return response()->json($e);
        }
    }
    //delete
    public function delete_product($id){
        try{
            $exist = Product::find($id);
            if(!$exist) return response()->json(['message'=> 'no product found']);
            $exist->delete();
            return response()->json(['message'=>'deleted successfully']);
        }catch(Exception $e){
            return response()->json($e);
        }
    }


    //data
    private function getData($request){
        return [
            'name'  => $request->name ,
            'price' => $request->price ,
            'amount' => $request->amount ,
            'discount' => $request->discount ,
            'categroy_id' => $request->categroy_id ,
            'brand_id' => $request->brand_id ,
        ];
    }

    //validation
    private function validation($request){
        Validator::make($request->all(),[
            'name'  => 'required' ,
            'price' => 'required' ,
            'amount' => 'required' ,
            'discount' => 'required' ,
            'categroy_id' => 'required' ,
            'brand_id' => 'required' ,
        ])->validate();
    }
}
