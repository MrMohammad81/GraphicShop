<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequests;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreRequests $request)
    {
        // validate
        $validatedData = $request->validated();
        $createdCategory = Category::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug']
        ]);

        if (!$createdCategory)
            return back()->with('field', 'دسته بندی ایجاد نشد');
        return back()->with('success','دسته بندی با موفقیت ایجاد شد');
    }

    public function all()
    {
        $categories = Category::all();
        return view('admin.categories.all',compact('categories'));
    }

}