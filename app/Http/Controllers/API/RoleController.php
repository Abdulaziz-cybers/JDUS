<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        return response()->json($role::with('users')->get());
    }
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        Role::create($validator);
        return response()->json('Role created!');
    }
    public function update(Request $request, Role $role)
    {
        $validator = $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        $role->update($validator);
        return response()->json('Role updated!');
    }
    public function destroy($id)
    {
        Role::destroy($id);
    }
}
