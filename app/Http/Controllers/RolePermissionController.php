<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function __construct(){
    }

    function addPermissions(Request $req, $id){
        $selectedRole = Role::findOrFail($id);
        $req->validate([
            "permission_ids" => "required|array",
            "permission.*" => "required|numeric|exists:permissions,id"
        ]);

        $syncRole = $selectedRole->syncPermissions($req->permission_ids);

        return response()->json([
            "msg" => "permissions created successfully",
            "data" => Role::with('permissions')->findOrFail($id)
        ], Response::HTTP_CREATED);
    }
}
