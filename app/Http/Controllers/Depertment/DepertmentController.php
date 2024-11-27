<?php

namespace App\Http\Controllers\Depertment;

use App\Http\Controllers\Controller;
use App\Models\Depertment\Depertment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepertmentController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depertment = Depertment::all();
        return Inertia::render('User/Depertment', [
            'depertment'    => $depertment
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
            'name' => 'required'
        ]);

        $depertment = Depertment::create([
            'name'  => $data['name']
        ]);

        return redirect()-> back() -> with('success', $depertment .' Depertment created successfully.');
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
    public function update(Request $request, Depertment $depertment)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $depertment->update([
            'name' => $request->name,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depertment $depertment)
    {
        $depertment -> delete();
    }
}
