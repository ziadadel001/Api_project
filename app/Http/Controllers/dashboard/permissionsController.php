<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class permissionsController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view permissions', only: ['index']),
            new Middleware('permission:edit permissions', only: ['edit', 'update']),
            new Middleware('permission:create permissions', only: ['create']),
            new Middleware('permission:delete permissions', only: ['destroy']),
        ];
    }

    //* this method will show permissions page
    public function index()
    {
        $permissions = Permission::latest()->paginate(5);
        return view('permissions.list', compact('permissions'));
    }

    //* this method will show create permission page
    public function create()
    {
        return view('permissions.create');
    }

    //* this method will insert permission in DB
    public function store(Request $request)
    {
        // validate the permission name 
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);

        if ($validator->passes()) {
            // store the permission in DB if vaild 
            Permission::create(['name' => $request->name]);
            return redirect()->route('permission.index')->with('success', 'Permission added successfully.');
        } else {
            return redirect()->route('permission.create')->withInput()->withErrors($validator);
        }
    }
    //* this method will show edit permission page
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }



    //* this method will update permission in DB
    public function update(int $id, Request $request)
    {
        //find the permission
        $permission = Permission::findOrFail($id);

        // validate the permission name 
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id . ',id'
        ]);

        if ($validator->passes()) {
            // update the permission in DB if vaild 
            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('permission.index')->with('success', 'Permission updated successfully.');
        } else {
            return redirect()->route('permission.edit', $id)->withInput()->withErrors($validator);
        }
    }

    //* this method will delete permission in DB
    public function destroy(Request $request)
    {
        $id = $request->id;
        $permission = Permission::find($id);

        if ($permission == null) {
            session()->flash('error', 'permission not found');
            return response()->json([
                'status' => false
            ]);
        }

        $permission->delete();
        session()->flash('success', 'permission deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
