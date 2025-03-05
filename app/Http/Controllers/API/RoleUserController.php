<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function index()
    {

    }
    public function store(RoleUserRequest $request)
    {
        $validator = $request->validated();
        $user = User::query()
            ->find($validator['user_id']);
        $user->roles()->attach($validator['role_id']);
        return response()->json([
            'success' => true,
        ]);
    }
    public function destroy(RoleUserRequest $request)
    {
        $validator = $request->validated();
        $user = User::query()
            ->find($validator['user_id']);
        $user->roles()->detach($validator['role_id']);
        return response()->json([
            'success' => true,
        ]);
    }
}
