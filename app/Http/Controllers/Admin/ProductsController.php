<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Utilities\imageUploader;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function create()
    {
         $categories = Category::all();
        return view('admin.products.add',compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $admin = User::where('role' , 'admin')->first();

        $createdProduct =  Product::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'owner_id' => $admin->id]);

        try {
            $thumbnailUrl = $validatedData['thumbnail_url'];
            $demoUrl = $validatedData['demo_url'];
            $sourceUrl = $validatedData['source_url'];

            // save images
            $basePatch = 'products/'.$createdProduct->id . '/';
            $sourceImageFullPatch = $basePatch.'source_url_'.$sourceUrl->getClientOriginalName();

            $image = [
                'thumbnail_url' => $thumbnailUrl,
                'demo_url' => $demoUrl,
            ];

            # demo image
            ImageUploader::uploadMany($image ,$basePatch);
            #source image
            ImageUploader::upload($sourceUrl,$sourceImageFullPatch , 'local_storage');

            $updatedProduct =  $createdProduct->update([
                'thumbnail_url' => $thumbnailUrl,
                'demo_url' => $demoUrl,
                'source_url' => $sourceImageFullPatch
             ]);

            if (!$updatedProduct)
            {
                throw new \Exception('خطا در آپبود تصاویر');
            }
            return back()->with('success','محصول با موفقیت ایجاد شد');

        }catch (\Exception $exception)
        {
           return back()->with('filed',$exception->getMessage());
        }
    }

    public function all()
    {
        return view('admin.products.all');
    }
}
