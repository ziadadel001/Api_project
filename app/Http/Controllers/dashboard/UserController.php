<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller  implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:edit users', only: ['edit', 'update']),
            new Middleware('permission:create users', only: ['create']),
            new Middleware('permission:delete users', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(5);
        return view('users.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {


        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->syncRoles($request->role);

            // Optional: Send verification email
            // $user->sendEmailVerificationNotification();

            return redirect()->route('user.index')->with('success', 'user Added successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $HasRoles = $user->roles->pluck('id');
        return view('users.edit', compact('user', 'roles', 'HasRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = user::findOrFail($id);


        $user->update($request->validated());

        $user->syncRoles($request->role);
        return redirect()->route('user.index')->with('success', 'user Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if ($user == null) {
            session()->flash('error', 'user not found');
            return response()->json([
                'status' => false
            ]);
        }

        $user->delete();
        session()->flash('success', 'user deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
