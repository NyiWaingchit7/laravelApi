<?php

namespace App\Models;

use App\Models\User;
use App\Models\Location;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'total_price',
        'date_of_delievery',
        'user_id',
        'location_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function location(){
        return $this->belongsTo(Location::class,'location_id');
    }
    public function items(){
        return $this->hasMany(OrderItem::class);
    }
}