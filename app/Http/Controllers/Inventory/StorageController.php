<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StorageController extends Controller
{
    /**
     * Display a listing of the resource.
     **/
    public function index()
    {
        $storage = Storage::all(); 
        return Inertia::render('Inventory/Storage', [
            'storage' => $storage
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
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'size' => 'required',
            
        ]);

        $storage = Storage::create([
            'name'  => $data['name'],
            'address'  => $data['address'],
            'size'  => $data['size'],
        ]);

        return redirect()-> back() -> with('success', $storage .' Storage created successfully.');
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
    public function update(Request $request, Storage $storage)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'size' => 'required',
        ]);

        $storage->update([
            'name' => $request->name,
            'address' => $request->address,
            'size' => $request->size,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Storage $storage)
    {
        $storage -> delete();
    }
}
