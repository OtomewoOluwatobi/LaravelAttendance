<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
    }

    function index(Request $req)
    {
        $roles = Permission::with('roles')->paginate();
        return response()->json([
            "data" => $roles
        ], Response::HTTP_OK);
    }

    function store(Request $req, Response $res)
    {
        $req->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'required|string|unique:permissions,name',
        ]);

        $newPermissions = [];

        foreach ($req->permissions as $key => $permission) {
            $permissions = [];

            $permissions['guard_name'] = strtolower("api");
            $permissions['name'] = strtolower($permission);
            $permissions['created_at'] = now();
            $permissions['updated_at'] = now();

            array_push($newPermissions, $permissions);
        }

        Permission::insert($newPermissions);

        return response()->json([
            "msg" => "permissions created successfully",
            "data" => $newPermissions
        ], Response::HTTP_CREATED);
    }

    function show($id){
        $selected_permission = Permission::with('roles')->find($id);

        return response()->json([
            "data" => $selected_permission
        ], Response::HTTP_OK);
    }

    function update(Request $req, $id)
    {
        $selected_permission = Permission::findOrFail($id);

        $req->validate([
            'name' => 'required|string|unique:roles,name,'.$id,
        ]);

        $selected_permission->name = $req->input('name');
        $selected_permission->save();

        return response()->json([
            "msg" => $selected_permission->name." saved successfully",
            "data" => $selected_permission
        ], Response::HTTP_CREATED);
    }

    function destroy($id)
    {
        $selected_permission = Permission::withCount('permissions')->findOrFail($id);
        if($selected_permission->roles_count > 0){
            return response()->json([
                "msg" => $selected_permission->name." can't be deleted it assigned to a role",
            ], Response::HTTP_UNAUTHORIZED);
        }
        $selected_permission->delete();
        return response()->json([
            "msg" => "selected permission has been deletes successfully",
        ], Response::HTTP_OK);
    }
}
