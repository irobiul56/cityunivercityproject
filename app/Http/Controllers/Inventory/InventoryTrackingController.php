<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\ProductCategoryModel;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Milon\Barcode\DNS1D;
use Auth;
use Str;

class InventoryTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        
        $barcodeGenerator = new DNS1D();

        $products->transform(function ($product) use ($barcodeGenerator) {
            $product->barcode_html = $barcodeGenerator->getBarcodeHTML($product->sku, 'C128');
            return $product;
        });
        return Inertia::render('Inventory/InventoryTracking', [
            'product' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
