<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use App\Models\RoleHasPermission;
use App\Models\ModelHasPermission;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $roleHasPermissions = RoleHasPermission::all();
        $modelHasRoles = ModelHasRole::all();
        $modelHasPermissions = ModelHasPermission::all();
        $users = User::all();
        return view('menus.userManagement.rollAndPermission', compact('roles', 'permissions', 'roleHasPermissions', 'modelHasPermissions', 'modelHasRoles', 'users'));
    }

    public function assignRole(Request $request)
    {
        // Ambil user berdasarkan ID dari request
        $user = User::find($request->user); // Pastikan ini adalah ID user

        // Cek apakah role ditemukan
        $role = Role::find($request->role); // Ambil role berdasarkan ID role

        // Pastikan user dan role ditemukan
        if ($user && $role) {
            // Gunakan assignRole dari Spatie untuk memberikan role ke user
            $user->assignRole($role->name); // Ini akan otomatis menyimpan ke model_has_roles
            notify()->success('User was assigned successfully! ✍️');
        } else {
            notify()->error('Failed to assign role! ⚠️');
        }

        return redirect()->back();
    }

    public function assignPermission(Request $request)
    {
        // Ambil user berdasarkan ID dari request
        $user = User::find($request->user); // Pastikan ini adalah ID user

        // Cek apakah role ditemukan
        $permission = Permission::find($request->permission); // Ambil permission berdasarkan ID permission

        // Pastikan user dan permission ditemukan
        if ($user && $permission) {
            $user->givePermissionTo($permission->name);
            notify()->success('User was assigned successfully! ✍️');
        } else {
            notify()->error('Failed to assign permission! ⚠️');
        }

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $modelId = $request->model_id;
        $roleId = $request->role_id;
        $permissionId = $request->permission_id;
        if ($request->assign == 'modelRole') {
            $user = User::find($modelId);
            $role = Role::find($roleId);
            $user->removeRole($role->name);
            notify()->success('Model was unassigned successfully! 👌');
            return redirect()->back();
        } elseif ($request->assign == 'modelPermission') {
            $user = User::find($modelId);
            $permission = Permission::find($permissionId);
            $user->revokePermissionTo($permission->name);
            notify()->success('Model was unassigned successfully! 👌');
            return redirect()->back();
        }
        // notify()->success('Permission and role were removed successfully! 👌');
        // return redirect()->back();
    }

    public function unassign(Request $request, $id)
    {

    }
}
