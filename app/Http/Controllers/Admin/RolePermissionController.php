<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    function index()  {
        $filters = request()->all('name');
        $roles = Role::orderBy('name','ASC');
        if(request()->name){
            $roles = $roles->where('name','Like','%'.request()->name.'%');
        }
        $roles = $roles->paginate(request()->perPage ?? $this->per_page)
            ->withQueryString()->through(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]);
        return Inertia::render('Admin/Role/RoleList',compact('roles','filters'));
    }
}
