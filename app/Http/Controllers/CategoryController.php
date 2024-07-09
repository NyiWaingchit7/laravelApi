<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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
}
