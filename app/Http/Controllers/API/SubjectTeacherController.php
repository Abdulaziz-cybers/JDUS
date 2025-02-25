<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectTeacherController extends Controller
{
    // Get all groups with their subjects
    public function store(Request $request)
    {
        $validator = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $user = User::query()
            ->find($validator['user_id']);
        $user->subjects()->attach($validator['subject_id']);
        return response()->json([
            'success' => true,
        ]);
    }
    public function destroy(Request $request)
    {
        $validator = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $user = User::query()
            ->find($validator['user_id']);
        $user->subjects()->detach($validator['subject_id']);
        return response()->json([
            'success' => true,
        ]);
    }
}
