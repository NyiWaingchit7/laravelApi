<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Location;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //index
    public function index(){
        $orders = Order::with('user')->paginate(20);
        if($orders){
            foreach($orders as $order){
                foreach($order->items as $order_item){
                    $product = Product::where('id', $order_item->product_id)->pluck('name');
                    $order_item->product_name = $product[0];
                }
            }
            return response()->json($orders, 200);
        }else{
            return response()->json(['message'=>'no order found']);
        }

    }
    //show
    public function detail($id){
        $orders = Order::find($id);
        if(!$orders) return response()->json(['message'=> ' no order found']);
        foreach($orders->items as $order){
                $product = Product::where('id', $order->product_id)->pluck('name');
                $order->product_name = $product[0];
        }
        return response()->json($orders, 200);
    }
    //store
    public function store(Request $request){
        try{
            $this->validation($request);
            $location = Location::where('user_id',Auth::id())->first();
            $order = new Order();
            $order->location_id = $location->id;
            $order->user_id = Auth::id();
            $order->total_price = $request->total_price;
            $order->date_of_delivery = $request->date_of_delivery;
            $order->save();

            foreach($request->order_items as $order_item){
                $item = new OrderItem();
                $item->order_id = $order->id;
                $item->price = $order_item['price'];
                $item->product_id = $order_item['product_id'];
                $item->quantity = $order_item['quantity'];
                $item->save();
                $product = Product::where('id',$order_item['product_id'])->first();
                $product->amount -= $order_item['quantity'];
                $product->save();
            }
            return response()->json(['message'=>'order success.'], 200);
        }catch(Exception $e){
            return response()->json($e);
        }
    }
    //order_items
    public function get_order_items($id){
        $order_items = OrderItem::where('order_id',$id)->get();
        if(!$order_items) return response()->json(['message'=>'no items found']);
        foreach($order_items as $order_item){
            $product = Product::where('id', $order_item->product_id)->pluck('name');
            $order_item->product_name = $product[0];
        }
        return response()->json($order_items, 200);
    }
    //user_order
    public function get_user_orders($id){
        $orders = Order::where('user_id',$id)->with('items',function($query){
            $query->orderBy('created_at','desc');
        })->get();
        if(!$orders) return response()->json(['message'=>'no order found']);
        foreach($orders as $order){
            foreach($order->items as $order_item){
                $product = Product::where('id',$order_item->product_id)->pluck('name');
                $order->product_name = $product[0];
            }
        }
        return response()->json($orders, 200);
    }
    //change status
    public function change_status($id,Request $request){
        $order = Order::find($id);
        if(!$order) return response()->json(['message'=>'no order found']);
        $order->status = $request->status;
        $order->save();
        return response()->json(['message'=>'status changed successfully'], 200);
    }

    //validation
    private function validation($request){
        Validator::make($request->all(),[
            'order_items' => 'required',
            'date_of_delivery' => 'required',

        ])->validate();
    }
}
