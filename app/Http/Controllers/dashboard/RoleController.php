<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
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
            new Middleware('permission:edit roles', only: ['edit']),
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
    public function store(Request $request)
    {
        // validate the Role name 
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        if ($validator->passes()) {
            // store the Role in DB if vaild 
            $role =  Role::create(['name' => $request->name]);
            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }
            return redirect()->route('role.index')->with('success', 'Role added successfully.');
        } else {
            return redirect()->route('role.create')->withInput()->withErrors($validator);
        }
    }




    //* this method will show edit role page
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $HasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.edit', compact('role', 'permissions', 'HasPermissions'));
    }



    //* this method will update role in DB
    public function update(int $id, Request $request)
    {
        $role = Role::findOrFail($id);

        // validate the Role name 
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,' . $id . ',id'
        ]);

        if ($validator->passes()) {
            $role->name = $request->name;
            $role->save();
            if (!empty($request->permission)) {
                $role->syncPermissions($request->permission);
            } else {
                $role->syncPermissions([]);
            }
            return redirect()->route('role.index')->with('success', 'Role updated successfully.');
        } else {
            return redirect()->route('role.edit', $id)->withInput()->withErrors($validator);
        }
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
