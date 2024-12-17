<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $room = Room::all(); 
        return Inertia::render('Inventory/Room', [
            'room' => $room
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
            'room_number' => 'required',
            'floor' => 'required',
            'size' => 'required',
            'type' => 'required',
        ]);

        $room = Room::create([
            'name'  => $data['name'],
            'room_number'  => $data['room_number'],
            'floor'  => $data['floor'],
            'size'  => $data['size'],
            'type'  => $data['type'],
        ]);

        return redirect()-> back() -> with('success', $room .' Room created successfully.');
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
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required',
            'room_number' => 'required',
            'floor' => 'required',
            'size' => 'required',
            'type' => 'required',
        ]);

        $room->update([
            'name' => $request->name,
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'size' => $request->size,
            'type' => $request->type,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room -> delete();
    }
}
