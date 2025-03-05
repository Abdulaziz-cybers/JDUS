<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectTeacherRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectTeacherController extends Controller
{
    // Get all groups with their subjects
    public function store(SubjectTeacherRequest $request)
    {
        $validator = $request->validated();
        $user = User::query()
            ->find($validator['teacher_id']);
        $user->subjects()->attach($validator['subject_id']);
        return response()->json([
            'success' => true,
        ]);
    }
    public function destroy(SubjectTeacherRequest $request)
    {
        $validator = $request->validated();
        $user = User::query()
            ->find($validator['teacher_id']);
        $user->subjects()->detach($validator['subject_id']);
        return response()->json([
            'success' => true,
        ]);
    }
}
