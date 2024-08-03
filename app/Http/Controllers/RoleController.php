<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
    }

    function index(Request $req)
    {
        $roles = Role::with('permissions')->paginate();
        return response()->json([
            "data" => $roles
        ], Response::HTTP_OK);
    }

    function store(Request $req, Response $res)
    {
        $req->validate([
           'name' => 'required|string|unique:roles,name',
        ]);
        $newRole = Role::create([
            'name' => strtolower($req->input('name'))
        ]);
        return response()->json([
            "msg" => "role created successfully",
            "data" => $newRole
        ], Response::HTTP_CREATED);
    }

    function show($id){
        $selected_role = Role::with('permissions')->find($id);

        return response()->json([
            "data" => $selected_role
        ], Response::HTTP_OK);
    }

    function update(Request $req, $id)
    {
        $selected_role = Role::findOrFail($id);

        $req->validate([
            'role_name' => 'required|string|unique:roles,role_name,'.$id,
        ]);

        $selected_role->role_name = $req->input('role_name');
        $selected_role->save();

        return response()->json([
            "msg" => $selected_role->role_name." saved successfully",
            "data" => $selected_role
        ], Response::HTTP_CREATED);
    }

    function destroy($id)
    {
        $selected_role = Role::withCount('permissions')->findOrFail($id);
        if($selected_role->permissions_count > 0){
            return response()->json([
                "msg" => $selected_role->role_name." can't be deleted it has some permissions assigned",
            ], Response::HTTP_UNAUTHORIZED);
        }
        $selected_role->delete();
        return response()->json([
            "msg" => "selected role has been deletes successfully",
        ], Response::HTTP_OK);
    }
}
