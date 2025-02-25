<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupSubjectController extends Controller
{
    // Get all groups with their subjects
    public function store(Request $request)
    {
        $validator = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'group_id' => 'required|exists:groups,id',
        ]);
        $user = Group::query()
            ->find($validator['group_id']);
        $user->subjects()->attach($validator['subject_id']);
        return response()->json([
            'success' => true,
        ]);
    }
    public function destroy(Request $request)
    {
        $validator = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'group_id' => 'required|exists:group,id',
        ]);
        $user = Group::query()
            ->find($validator['group_id']);
        $user->subjects()->detach($validator['subject_id']);
        return response()->json([
            'success' => true,
        ]);
    }
}
