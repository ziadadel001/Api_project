<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'image',
    ];
    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
