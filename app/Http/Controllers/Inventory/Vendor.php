<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Vendor as ModelsVendor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Vendor extends Controller
{
    /**
     * Display a listing of the resource.
     **/
    public function index()
    {
        $vendor = ModelsVendor::all(); 
        return Inertia::render('Inventory/Vendor', [
            'vendor' => $vendor
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
     * Store a newly created resource in Vendor.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'company_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            
        ]);

        $Vendor = ModelsVendor::create([
            'name'  => $data['name'],
            'company_name'  => $data['company_name'],
            'address'  => $data['address'],
            'phone'  => $data['phone'],
        ]);

        return redirect()-> back() -> with('success', $Vendor .' Vendor created successfully.');
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
     * Update the specified resource in Vendor.
     */
    public function update(Request $request, ModelsVendor $Vendor)
    {
        $request->validate([
            'name' => 'required',
            'company_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        $Vendor->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);
    }

    /**
     * Remove the specified resource from Vendor.
     */
    public function destroy(ModelsVendor $Vendor)
    {
        $Vendor -> delete();
    }
}
