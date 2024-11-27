<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\PermissionModel;
use App\Models\User\RoleModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = RoleModel::with('permissions')->get();
        $permission = PermissionModel::all();
        return Inertia::render('User/Role', [
            'role'          => $role,
            'permission'    => $permission,
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

        $role = RoleModel::create([
            'name' => $data['name'],
        ]);

        // Attach permissions to the role
        $role->permissions()->attach($request->permission_id); // `permission_id` is an array
        
        return redirect()->back()->with('success', $role->name . ' role created successfully.');
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'permission_id' => 'array', // Ensure permission_id is an array
        ]);

        // Find the role to update
        $role = RoleModel::findOrFail($id);

        // Update role name
        $role->update([
            'name' => $data['name'],
        ]);

        // Sync permissions with the updated list
        $role->permissions()->sync($request->permission_id);

        return redirect()->back()->with('success', $role->name . ' role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = RoleModel::findOrFail($id);

        // Detach any associated permissions before deleting
        $role->permissions()->detach();

        // Delete the role
        $role->delete();

        return redirect()->back()->with('success', 'Role deleted successfully.');
    }
}
