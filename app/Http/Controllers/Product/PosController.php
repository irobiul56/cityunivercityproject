<?php

namespace App\Http\Controllers\Product;
use App\Models\Customer;
use App\Models\Product\Product;
use App\Models\invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Auth;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::all();
        $customers = Customer::all();
        return Inertia::render('Frontend/Pos', [
            'product'=> $product,
            'customers'=> $customers
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
        // Validate the request
    $data = $request->validate([
        'invoice_items' => 'required|array',
        'total_qnt' => 'required|integer',
        'sub_total' => 'required|numeric',
        'tax' => 'required|numeric',
        'discount' => 'nullable|numeric',
        'total' => 'required|numeric',
        'payment_type' => 'required|string',
        'customer_id' => 'nullable|exists:customers,id',
    ]);


    // Create the invoice (adjust this based on your database structure)
    $invoice = Invoice::create([
        'customer_id' => $data['customer_id'],
        'user_id'   => Auth::user()->id,
        'total_qnt' => $data['total_qnt'],
        'sub_total' => $data['sub_total'],
        'tax' => $data['tax'],
        'discount' => $data['discount'] ?? 0,
        'total' => $data['total'],
        'payment_type' => $data['payment_type'],
        'invoice_items' => json_encode($request->invoice_items), // Store items as JSON
    ]);


    return redirect()->route('pos.show', $invoice->id)->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with('customer')->findOrFail($id); // Get the invoice along with customer details
         // Decode the JSON `invoice_items` into an array
        $invoice->invoice_items = json_decode($invoice->invoice_items, true);
        
        return inertia('Product/Invoice', [
            'invoice' => $invoice
        ]);
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
