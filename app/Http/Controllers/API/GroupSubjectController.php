<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupSubjectRequest;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupSubjectController extends Controller
{
    // Get all groups with their subjects
    public function store(GroupSubjectRequest $request)
    {
        $validator = $request->validated();
        $user = Group::query()
            ->find($validator['group_id']);
        $user->subjects()->attach($validator['subject_id']);
        return response()->json([
            'success' => true,
        ]);
    }
    public function destroy(GroupSubjectRequest $request)
    {
        $validator = $request->validated();
        $user = Group::query()
            ->find($validator['group_id']);
        $user->subjects()->detach($validator['subject_id']);
        return response()->json([
            'success' => true,
        ]);
    }
}
