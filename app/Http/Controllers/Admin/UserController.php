<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use JeroenNoten\LaravelAdminLte\View\Components\Tool\Datatable;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend/users.index');
    }

    public function allUsers()
    {
        $users =  DB::table('roles')
        ->join("model_has_roles", "model_has_roles.role_id", "=", "roles.id")
        ->rightJoin("users", "users.id", "=", "model_has_roles.model_id")
        ->select('roles.name as rol', 'users.name','users.id','users.email')
        ->get();
     
        return datatables()->of($users)->toJson();
    }
     public function create()
    {
        $roles = Role::all();
        return view('backend/users.crear', compact('roles'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'roles' => 'required',
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required',
        ]);

        $roles = $request->input("roles");
        $user = User::create([

            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),

        ]);
        $user->roles()->sync($roles);
        return redirect()->route("users.index");
    }

    
    public function roles()
    {
        $roles = Role::all();
        return $roles;
    }
    

    public function edit($id)
    {
        $user = User::findOrfail($id);
        if($user){
            return response()->json([
                'status' => 200,
                'user' => $user,
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'El Usuario no existe'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id )
    {
        
        $user = User::findOrfail($id);
        $user ->roles()->sync($request->roles);
        return response()->json([
            'status' => 200,
        ]);
    }

   
}
