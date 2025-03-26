<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:role-read'], ['only' => ['index']]);
        $this->middleware(['permission:role-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:role-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:role-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();
        $roles = $roles->whereNotIn('name',['seller']);
        return view('admin.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.role.createrole');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'gurad_name'=>'required|in:web,seller',
            ]
        );
        DB::beginTransaction();
        try {
            Role::create([
                'name'=>strtolower($request->name),
                'gurad_name'=>$request->gurad_name
            ]);
            request()->session()->flash('success', 'Role Created Successfully');
            DB::commit();
            return redirect()->route('role.index');
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash(
                'error',
                $th->getMessage()
            );
            return redirect()->back()->withInput();
        }
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
        $role = Role::findorfail($id);
        $selectedPermissions = $role->permissions()->pluck('id')->toArray();

        $permissions = Permission::get()->groupBy(function ($item, $key) {
            return explode('-', $item->name)[0];
        });
        return view('admin.role.form', compact('role', 'permissions', 'selectedPermissions'));
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
        $request->validate(
            [
                'name' => 'required',
                'permission' => ['required', 'array'],
                'permission.*' => ['required', 'exists:permissions,id']
            ]
        );
        $role = Role::findorfail($id);
        try {
            $role->update(['name' => strtolower($request->name)]);
            $role->permissions()->sync($request->permission);
            request()->session()->flash('success', 'Role Updated Successfully');
            return redirect()->route('role.index');
        } catch (\Throwable $th) {
            request()->session()->flash(
                'error',
                $th->getMessage()
            );
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
