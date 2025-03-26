<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\UserShippingAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\AdminUpdatePasswordRequest;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\Facades\Auth;
use App\Models\New_Customer;

class UserController extends Controller
{
    protected $admin=null;
    public function __construct(User $admin)
    {
        $this->middleware(['permission:user-read'], ['only' => ['index']]);
        $this->middleware(['permission:user-create'], ['only' => ['create', 'store']]);
        // $this->middleware(['permission:user-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:user-delete'], ['only' => ['destroy']]);
        $this->admin=$admin;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $data = [
            'userCount' => User::count(),
            'activeCount' => User::where('status',1)->count(),
            'inActiveCount' => User::where('status',0)->count(),
            'customerCount' => New_Customer::where('status','1')->count()
        ];
        return view('admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $roles = Role::whereNotIn('name',['seller'])->get();
        return view('admin.user.form', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = (new CreateNewUser())->create($request->all());
            $user->syncRoles([$request->role_id]);
            session()->flash('success', 'User Created Successfully');
            return redirect()->route('user.index');
        } catch (\Throwable $th) {
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()->withErrors($th->validator->errors())->withInput();
            }
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load('activities');
        $user->loadCount('product');
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(Auth::user()->id == $user->id || Auth::user()->hasRole('super admin'))
        {
            $roles = Role::whereNotIn('name',['seller'])->get();
            $userRole = $user->getRoleName();
            $userRole = Role::where('name',$userRole)->first();
            return view('admin.user.form', compact('user', 'roles','userRole'));
        }
        return back()->with('error','You are not allowed to edit other users profile.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // dd($request->all());
        try {
            (new UpdateUserProfileInformation())->update($user, $request->all());
            session()->flash('success', 'User updated Successfully');
            $user->syncRoles([$request->role_id]);
            return redirect()->route('user.show',$user->id);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $user = User::find($id);
        try {
            $user->delete();
            session()->flash('success', 'User deleted Successfully');
            return redirect()->route('user.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function updatePassword(AdminUpdatePasswordRequest $request)
    {
        $this->admin=auth()->user();
        if(!$this->admin)
        {
            $response=[
                'error'=>true,
                'msg'=>'Unauthorized Access !!'
            ];
            return response()->json($response,200);
        }

        $old_password=Hash::check($request->current_password,$this->admin->password);
        if(!$old_password)
        {
            $response=[
                'old_password'=>true,
                'msg'=>'Sorry !! Current Password Doesnot Match Our Records'
            ];
            return response()->json($response,200);
        }
        
        $this->admin->password=bcrypt($request->password);
        DB::beginTransaction();
        try{
            $this->admin->save();
            DB::commit();
            $response=[
                'success'=>true,
                'msg'=>'Password Updated Successfully !!'
            ];
            return response()->json($response,200);
        }catch(\Throwable $th){
            $response=[
                'error'=>true,
                'msg'=>$th->getMessage()
            ];
            session()->flash('success','Password Updated Successfully !!');
            return response()->json($response,200);
        }
       


    }



}
