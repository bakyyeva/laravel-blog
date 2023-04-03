<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryFilterRequest;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Requests\Category\ChangeStatusRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function index(CategoryFilterRequest $request)
    {

        $parentCategories = Category::all();
        $users = User::all();

        $categories = Category::query()
            ->with(['parentCategory:id,name', 'user:id,name'])
            ->name($request->name)
            ->slug($request->slug)
            ->description($request->description)
            ->order($request->order)
            ->parentCategory($request->parent_id)
            ->user($request->user_id)
            ->status($request->status)
            ->featureStatus($request->feature_status)
            ->paginate(5);

        return view('admin.categories.list', compact('categories', 'parentCategories', 'users'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create-update', compact('categories'));
    }

    public function store(CategoryStoreRequest $request)
    {
        $slug = Str::slug($request->slug);

        try {
            Category::create([
                'name' => $request->name,
                'slug' => is_null($this->slugCheck($slug)) ? $slug : Str::slug($slug . time()),
                'color' => $request->color,
                'status' => $request->status ? 1 : 0,
                'image' => $request->image,
                'feature_status' => $request->feature_status ? 1 : 0,
                'description' => $request->description,
                'order' => $request->order,
                'seo_keywords' => $request->seo_keywords,
                'seo_description' => $request->seo_description,
                'parent_id' => $request->parent_id,
                'user_id' => auth()->id()
            ]);
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Kategori Kaydedildi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('category.index');
    }

    public function slugCheck(string $text)
    {
        return Category::query()->where('slug', $text)->first();
    }

    public function changeStatus(ChangeStatusRequest $request)
    {
        $categoryID = $request->id;

        $category = Category::query()->where('id', $categoryID)->firstOrFail();

        $oldStatus = $category->status;

        $category->status = !$category->status;
        $category->save();

        $statusText = ($oldStatus == 1 ? 'Aktif' : 'Pasif') . 'ten' . ($category->status == 1 ? 'Aktif' : 'Pasif');
        alert()->success('Başarılı', $category->name . ' status ' . $statusText . ' olarak güncellendi')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->back();
    }

    public function changeFeatureStatus(ChangeStatusRequest $request)
    {
        $categoryID = $request->id;

        $category = Category::query()->where('id', $categoryID)->firstOrFail();

        $oldStatus = $category->feature_status;

        $category->feature_status = !$category->feature_status;
        $category->save();

        $statusText = ($oldStatus == 1 ? 'Aktif' : 'Pasif') . 'ten' . ($category->feature_status== 1 ? 'Aktif' : 'Pasif');
        alert()->success('Başarılı', $category->name . ' Feature status ' . $statusText . ' olarak güncellendi')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->back();
    }

    public function delete(ChangeStatusRequest $request)
    {

        $categoryID =  $request->id;

        $category = Category::query()->where('id', $categoryID)->first();
        $category->categories()->delete();
        $category->delete();

        alert()->success('Başarılı', 'Kategory Silindi')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->back();
    }

    public function edit(Request $request, int $categoryID)
    {
        $categories = Category::all();

        $category = Category::query()->where('id', $categoryID)->first();

        if (is_null($category))
        {
            alert()->error('Hata', 'Kategory bulunamadı')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
            return redirect()->back();
        }
        return view('admin.categories.create-update', compact('category','categories'));
    }

    public function update(CategoryUpdateRequest $request, int $categoryID)
    {
        $category = Category::query()->find($categoryID);

        $slug = Str::slug($request->slug);
        $slugCheck = $this->slugCheck($slug);

        if((!is_null($slugCheck) && $slugCheck->id == $category->id) || is_null($slugCheck))
        {
            $category->slug = $slug;
        }
        elseif (!is_null($slugCheck) && $slugCheck->id != $category->id)
        {
            $category->slug = Str::slug($slug . time());
        }

//        if (!is_null($request->image))
//        {
//            $imageFile = $request->file('image');
//            $originalName = $imageFile->getClientOriginalName();
//            $originalExtencion = $imageFile->getClientOriginalExtension();
//            $fileName = Str::slug(explode(".", $originalName)[0]) . "." . $originalExtencion;
//            $folder = "categories";
//            $checkPath = "storage/" . $folder . "/";
//            $this->isFileExists($checkPath, $fileName);
//
//            if (file_exists(public_path($category->image)))
//            {
//                \Illuminate\Support\Facades\File::delete(public_path($category->image));
////                \File::delete(public_path($articleOld->image));
//            }
//            $imageFile->storeAs($folder,  $fileName, 'public');
//        }

        $category->name = $request->name;
        $category->color = $request->color;
        $category->status = $request->status ? 1 : 0;
        $category->image = !is_null($request->image) ? $request->image : $category->image;
        $category->feature_status = $request->feature_status ? 1 : 0;
        $category->description = $request->description;
        $category->order = $request->order;
        $category->seo_keywords = $request->seo_keywords;
        $category->seo_description = $request->seo_description;
        $category->parent_id = $request->parent_id;

        try {
           $category->save();
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Kategori güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route("category.index");
    }
}
