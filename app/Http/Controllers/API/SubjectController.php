<?php

namespace App\Http\Controllers\API;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController
{
    public function index(Request $request)
    {
        $query = Subject::query();

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
        $subjects = $query->paginate($perPage);

        return response()->json($subjects);
    }
    public function show(Subject $subject){
        return response()->json($subject);
    }
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required'
        ]);
        Subject::query()->create($validator);
        return response()->json('Subject created successfully');
    }
    public function update(Request $request, Subject $subject){
        $validator = $request->validate([
            'name' => 'required'
        ]);
        $subject->update($validator);
        return response()->json(['message' => 'Subject Updated'], 200);
    }
    public function delete(Subject $subject){
        $subject->delete();
    }
}
