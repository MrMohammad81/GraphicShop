<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Models\Category;
use App\Models\User;
use App\Utilities\FileRemover;
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
            'owner_id' => $admin->id]
        );

       if (!$this->uploadImages($createdProduct,$validatedData))
       {
           return back()->with('failed','خطا در ایجاد محصول');
       }
        return back()->with('success','محصول با موفقیت ایجاد شد');
    }

    public function all()
    {
        $products = Product::paginate(15);
        return view('admin.products.all' , compact('products'));
    }

    public function downlaodDemo($product_id)
    {
        $product = Product::findOrFail($product_id);

        return response()->download(public_path($product->demo_url));
    }

    public function downloadSource($product_id)
    {
        $product = Product::findOrFail($product_id);

        return response()->download(storage_path('app/local_storage/'.$product->source_url));
    }

    public function delete($product_id)
    {
        $product = Product::findOrFail($product_id);

        $product->delete();

        return back()->with('success' , 'محصول با موفقیت حذف شد');
    }

    public function edit($product_id)
    {
        $products = Product::findOrFail($product_id);
        $categories = Category::all();

        return view('admin.products.edit',compact('products','categories'));
    }

    public function update(UpdateRequest $request , $product_id)
    {
        $validatedData = $request->validated();

        $product = Product::findOrFail($product_id);

        $updatedProduct = $product->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
        ]);

        //$this->removeOldImages($product,$validatedData);

        if (!$this->uploadImages($product,$validatedData) or !$updatedProduct)
        {
            return back()->with('failed', 'خطا در بروزرسانی محصول');
        }
        return back()->with('success', 'محصول با موفقیت بروزرسانی شد');
    }

    private function uploadImages($UpdatedProduct ,$validatedData)
    {
        try {
            $thumbnailUrl = $validatedData['thumbnail_url'];
            $demoUrl = $validatedData['demo_url'];
            $sourceUrl = $validatedData['source_url'];
            $baseImagePatch = 'products/'.$UpdatedProduct->id . '/';
            $sourceImageFullPatch = null;
            $data = [];

            if (isset($sourceUrl))
            {
                $sourceImageFullPatch = $baseImagePatch.'source_url_'.$sourceUrl->getClientOriginalName();
                ImageUploader::upload($sourceUrl,$sourceImageFullPatch , 'local_storage');
                $data += ['source_url' => $sourceImageFullPatch];
            }

            if (isset($thumbnailUrl))
            {
                $fullPatch = $baseImagePatch . 'thumbnail_url' . '_' . $thumbnailUrl->getClientOriginalName();
                ImageUploader::upload($thumbnailUrl,$fullPatch);
                $data += ['thumbnail_url' => $fullPatch];
            }

            if (isset($demoUrl))
            {
                $fullPatch = $baseImagePatch . 'demo_url' . '_' . $demoUrl->getClientOriginalName();
                ImageUploader::upload($demoUrl,$fullPatch);
                $data += ['demo_url' => $fullPatch];
            }

            $updatedProduct =  $UpdatedProduct->update($data);

            if (!$updatedProduct)
            {
                throw new \Exception('خطا در آپبود تصاویر');
            }
            return true;

        }catch (\Exception $exception)
        {
            return false;
        }

    }

    private function removeOldImages($product, $validatedData)
    {
        if (isset($validatedData['source_url'])) {
            $sourcePath = $product->source_url;
            FileRemover::remove($sourcePath, 'local_storage');
        }

        if (isset($validatedData['thumbnail_url'])) {
            $thumbnailPath = $product->thumbnail_url;
            FileRemover::remove($thumbnailPath);
        }

        if (isset($validatedData['demo_url'])) {
            $demoPath = $product->demo_url;
            FileRemover::remove($demoPath);
        }
    }
}
