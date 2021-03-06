<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maklad\Permission\Models\Permission;
use Maklad\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Role/Index', [
            'list' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permisions = Permission::all();

        return Inertia::render('Role/EditAdd', [
            'permissions' => $permisions,
            'action' => 'Create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permission);

        return redirect()->route('roles.index')->with('message', 'Role created succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::get();
        $rolePermission = $role->permissions()->pluck('name')->toArray();
        // return $rolePermission;

        return Inertia::render('Role/EditAdd', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermission' => $rolePermission,
            'action' => 'Edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->update([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permission);

        return redirect()->route('roles.index')->with('message', 'Role updated succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return redirect()->route('roles.index')->with('message', 'Role deleted successfully');
    }
}
