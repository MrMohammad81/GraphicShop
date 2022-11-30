<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Http\Requests\Admin\Users\UpdateRquest;

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

    public function store(StoreRequest $request)
    {
        $validateData = $request->validated();

        $createdUser = User::create([
            'name' => $validateData['name'],
            'mobile' => $validateData['mobile'],
            'email' => $validateData['email'],
            'role' => $validateData['role'],
        ]);
        if (!$createdUser)
            return back()->with('failed' , 'خطا در ایحاد کاربر');
        return back()->with('success' , 'کاربر با موفقیت ایحاد شد');
    }

    public function delete($user_id)
    {
        $user = User::findOrFail($user_id);

        $user->delete();

        if (!$user)
            return back()->with('filed','خطا در حذف کاربر');
        return back()->with('success','کابر با موفقیت حذف شد');
    }

    public function edit($user_id)
    {
        $users = User::findOrFail($user_id);

        return view('admin.user.edit',compact('users'));
    }

    public function update(UpdateRquest $request,$user_id)
    {
        $validatedData = $request->validated();

        $user = User::findOrFail($user_id);

        $updatedData = $user->update([
            'name' => $validatedData['name'],
            'mobile' => $validatedData['mobile'],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
        ]);

        if (!$updatedData)
            return back()->with('failed' , 'خطا در بروزرسانی کاربر');
        return back()->with('success' , 'کاربر با موفقیت بروزرسانی شد');
    }

}
