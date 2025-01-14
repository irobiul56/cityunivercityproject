<?php

namespace App\Models;

use App\Models\Depertment\Depertment;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_requisition')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }


}
