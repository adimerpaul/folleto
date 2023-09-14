<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller{
    public function index(Request $request){
        $roles= Role::with('permissions')->get();
        return $roles;
    }
    public function store(Request $request){
        $request->validate([
            'name'=>'required|unique:roles',
        ]);
        $role=new Role();
        $role->name=$request->name;
        $role->save();
        return $role;
    }
}
