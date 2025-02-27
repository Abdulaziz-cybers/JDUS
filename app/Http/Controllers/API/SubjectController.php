<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
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
    public function store(SubjectRequest $request)
    {
        $validator = $request->validated();
        Subject::query()->create($validator);
        return response()->json('Subject created successfully');
    }
    public function update(SubjectRequest $request, Subject $subject){
        $validator = $request->validated();
        $subject->update($validator);
        return response()->json(['message' => 'Subject Updated'], 200);
    }
    public function destroy(Subject $subject){
        $subject->delete();
        return response()->json('Subject deleted successfully');
    }
}
