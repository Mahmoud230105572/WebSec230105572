<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoughtProduct extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id'];

    // the next lines will help us when showing a list of the purhcases with user names and products names
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
