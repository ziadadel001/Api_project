<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\permissions\StorePermissiosRequest;
use App\Http\Requests\permissions\UpdatePermissionsRequest;
use Illuminate\Http\Request;
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
    public function store(StorePermissiosRequest $request)
    {
        // store the permission in DB if vaild 
        Permission::create($request->validated());

        return redirect()->route('permission.index')->with('success', 'Permission added successfully.');
    }

    //* this method will show edit permission page
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }



    //* this method will update permission in DB
    public function update(Permission $permission, UpdatePermissionsRequest $request)
    {
        //find and update the permission
        $permission->update($request->validated());
        return redirect()->route('permission.index')->with('success', 'Permission updated successfully.');
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
