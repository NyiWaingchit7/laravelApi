<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'area',
        'user_id',
        'street',
        'building'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
