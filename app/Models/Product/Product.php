<?php

namespace App\Models\Product;

use App\Models\Requisition;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function storage()
    {
        return $this->belongsTo(Storage::class, 'store_id');
    }

    public function requisitions()
    {
        return $this->belongsToMany(Requisition::class, 'product_requisition')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    
}
