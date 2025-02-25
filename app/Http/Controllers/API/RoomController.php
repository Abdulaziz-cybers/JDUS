<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        // 🔹 Search functionality
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 🔹 Filter by a specific column (e.g., type)
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // 🔹 Sort results (default: created_at descending)
        $sortField = $request->get('sort_field', 'created_at'); // Default sorting field
        $sortOrder = $request->get('sort_order', 'desc'); // Default sorting order

        $query->orderBy($sortField, $sortOrder);

        // 🔹 Set default pagination if not provided
        $perPage = $request->get('per_page', 10); // Default 10 if not provided
        $rooms = $query->paginate($perPage);

        return response()->json($rooms);
    }
    public function show(Room $room)
    {
        return response()->json($room);
    }
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
        ]);
        Room::query()->create($validator);
        return response()->json(['message' => 'Room created successfully']);
    }
    public function update(Request $request, Room $room)
    {
        $validator = $request->validate([
            'name' => 'required',
        ]);
        $room->update($validator);
        return response()->json(['message' => 'Room updated successfully']);
    }
    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Room deleted successfully']);
    }
}
