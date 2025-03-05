<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::with('users')->get());
    }
    public function show(Role $role)
    {
        return response()->json($role);
    }
    public function store(RoleRequest $request)
    {
        $validator = $request->validated();
        Role::create($validator);
        return response()->json(['message'=>'Role created!']);
    }
    public function update(RoleRequest $request, Role $role)
    {
        $validator = $request->validated();
        $role->update($validator);
        return response()->json(['message' => 'Role updated successfully', 'role' => $role]);
    }
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
