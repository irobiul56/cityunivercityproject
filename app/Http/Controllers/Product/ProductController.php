<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\ProductCategoryModel;
use App\Models\Storage as ModelsStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
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
        return Inertia::render('Product/ProductList', [
            'product' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = ProductCategoryModel::all();
        $store = ModelsStorage::all();
        return Inertia::render('Product/CreateProduct', [
            'category'=> $category,
            'store'=> $store,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'store_id' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }
    
  
        $baseSku = strtoupper(Str::substr($request -> name, 0, 3));
        $randomNumber = rand(100, 999);
        $sku = $baseSku . '-' . $randomNumber;

        // Save product data to database (omitting actual save logic for brevity)
        Product::create([
            'name' => $request -> name,
            'description' => $request -> description,
            'price' => $request -> price,
            'stock' => $request -> stock,
            'image' => $imageName,
            'user_id' => Auth::user()->id,
            'sku' => $sku,
            'category_id' => $request -> category,
            'store_id' => $request -> store_id,
        ]);

        return redirect()->route('inventoryTracking.index')->with('success', 'Product created successfully.');

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
    public function edit(Product $product)
    {
        return Inertia::render('Product/ProductEdit', [
            'product'  => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category' => 'required|integer',
        // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image if present
    ]);

    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($product->image) {
            Storage::delete(public_path('images') . $product->image);
        }

        // Store the new image
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // Update the product with the new image name
        $product->image = $imageName;
    }

    // Update the product with new data
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'category_id' => $request->category,
        'user_id' => Auth::user()->id,
    ]);

    return redirect()->route('inventoryTracking.index')->with('success', 'Product Updated successfully.');
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product -> delete();

        return redirect() -> back() -> with('message', 'Product Deleted successfull');
    }
}
