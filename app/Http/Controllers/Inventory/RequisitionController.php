<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;

class RequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return Inertia::render('Inventory/RequsitionApplication', [
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
        
        // dd($request -> all());
        $validatedData = $request->validate([
                'description' => 'required',
                'invoice_items' => 'required|array',
                'invoice_items.*.id' => 'required|exists:products,id',
                'invoice_items.*.qnt' => 'required|integer|min:1',
            ]);

            $requisition = Requisition::create([
                'status' => 'pending',
                'is_regular' => 'yes',
                'description' => $request -> description,
                'requested_by' => Auth::user()->id,
            ]);
    
            foreach ($validatedData['invoice_items'] as $item) {
                $requisition->products()->attach($item['id'], ['quantity' => $item['qnt']]);
            }
    
            return redirect()->route('requisition.list')->with('message', 'Requisition created successfully!');
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

      /**
     * Approve the requisition.
     */
    public function approve($id)
    {
        $requisition = Requisition::findOrFail($id);

        if ($requisition->status !== 'pending') {
            return redirect()->back()->with('error', 'Requisition cannot be approved.');
        } else {
            $requisition->update(['status' => 'approved']);
            return redirect()->back()->with('success', 'Requisition approved successfully!');
        }
    }

    
      /**
     * Reject the requisition.
     */
    public function reject($id)
    {
        $requisition = Requisition::findOrFail($id);

        if ($requisition->status !== 'pending') {
            return back()->with('error', 'Requisition cannot be rejected.');
        }

        $requisition->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Requisition rejected successfully!');
    }


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
    public function destroy(Requisition $requisition)
    {
        $requisition->delete();

        return redirect()->route('requisition.list')->with('message', 'Requisition deleted successfully!');
    }
}
