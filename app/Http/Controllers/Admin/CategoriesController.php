<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequests;
use App\Http\Requests\Admin\Categories\UpdateRequest;
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
        $categories = Category::paginate(10);
        return view('admin.categories.all',compact('categories'));
    }

    public function delete($category_id)
    {
        $category = Category::find($category_id);

        $category->delete();

        return back()->with('success' , 'دسته بندی با موفقیت حذف شد');
    }

    public function edit($category_id)
    {
        $categories = Category::find($category_id);

        return view('admin.categories.edit' , compact('categories'));
    }

    public function update(UpdateRequest $request , $category_id)
    {
        $validatedData = $request->validated();
        $category = Category::find($category_id);

       $updatedCategory =  $category->update([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug']
        ]);

        if (!$updatedCategory)
            return back()->with('filed' , 'دسته بندی بروزرسانی نشد');
        return back()->with('success' , 'دسته بندی با موفقیت بروزرسانی شد');
    }
}
