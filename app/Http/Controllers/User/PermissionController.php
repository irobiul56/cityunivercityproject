<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\PermissionModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permission = PermissionModel::all();
        return Inertia::render('User/Permission', [
            'permission'    => $permission
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

        $permission = PermissionModel::create([
            'name'  => $data['name']
        ]);

        return redirect()-> back() -> with('success', $permission .' Permission created successfully.');
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
    public function update(Request $request, PermissionModel $permission)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $permission->update([
            'name' => $request->name,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermissionModel $permission)
    {
        $permission -> delete();
    }
}
