<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view roles', only: ['index']),
            new Middleware('permission:edit roles', only: ['edit', 'update']),
            new Middleware('permission:create roles', only: ['create']),
            new Middleware('permission:delete roles', only: ['destroy']),
        ];
    }
    //* this method will show roles page
    public function index()
    {
        $roles = Role::orderBy('name', 'ASC')->paginate(5);
        return view('roles.list', compact('roles'));
    }

    //* this method will show create role page
    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('roles.create', compact('permissions'));
    }

    //* this method will insert role in DB
    public function store(StoreRoleRequest $request)
    {

        // store the Role in DB if vaild 
        $role =  Role::create(['name' => $request->name]);

        if (!empty($request->permission)) {
            foreach ($request->permission as $name) {
                $role->givePermissionTo($name);
            }
        }
        return redirect()->route('role.index')->with('success', 'Role added successfully.');
    }




    //* this method will show edit role page
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $HasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.edit', compact('role', 'permissions', 'HasPermissions'));
    }



    //* this method will update role in DB
    public function update(Role $role, UpdateRoleRequest $request)
    {
        $role->update($request->validated());
        if (!empty($request->permission)) {
            $role->syncPermissions($request->permission);
        } else {
            $role->syncPermissions([]);
        }
        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }

    //* this method will delete role in DB
    public function destroy(Request $request)
    {
        $id = $request->id;
        $role = Role::find($id);

        if ($role == null) {
            session()->flash('error', 'Role not found');
            return response()->json([
                'status' => false
            ]);
        }

        $role->delete();
        session()->flash('success', 'Role deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
