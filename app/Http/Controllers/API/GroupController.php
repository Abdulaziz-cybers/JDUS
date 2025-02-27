<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Requests\GroupRequest;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::query();

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
        $groups = $query->paginate($perPage);

        return response()->json($groups);
    }
    public function show(Group $group){
        return response()->json($group);
    }
    public function store(GroupRequest $request)
    {
        $validator = $request->validated();
        Group::query()->create($validator);
        return response()->json(['message' => 'Group created successfully']);
    }
    public function update(GroupRequest $request, Group $group)
    {
        $validator = $request->validated();
        $group->update($validator);
        return response()->json(['message' => 'Group updated successfully']);
    }
    public function destroy(Group $group)
    {
        $group->delete();
        return response()->json(['message' => 'Group deleted successfully']);
    }
}
