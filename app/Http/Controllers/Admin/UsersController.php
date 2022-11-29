<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function all()
    {
        $users = User::paginate(10);

        return view('admin.user.all',compact('users'));
    }

    public function create()
    {
        return view('admin.user.add');
    }


}
