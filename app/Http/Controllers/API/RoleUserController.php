<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function index()
    {

    }
    public function store(Request $request)
    {
        $validator = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $user = User::query()
            ->find($validator['user_id']);
        $user->roles()->attach($validator['role_id']);
        return response()->json([
            'success' => true,
        ]);
    }
    public function destroy(Request $request)
    {
        $validator = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $user = User::query()
            ->find($validator['user_id']);
        $user->roles()->detach($validator['role_id']);
        return response()->json([
            'success' => true,
        ]);
    }
}
