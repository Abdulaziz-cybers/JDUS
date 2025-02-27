<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Http\Requests\GroupMemberRequest;

class GroupMemberController extends Controller
{
    public function store(GroupMemberRequest $request)
    {
        $validator = $request->validated();
        $group = Group::query()->findOrFail(request()->get($validator['group_id']));
        $group->users()->attach($validator['user_id']);
        return response()->json(['message' => 'Group added successfully']);
    }
    public function destroy(GroupMemberRequest $request)
    {
        $validator = $request->validated();
        $group = Group::query()->findOrFail(request()->get($validator['group_id']));
        $group->users()->detach($validator['user_id']);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
