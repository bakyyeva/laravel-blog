<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleFilterRequest;
use App\Http\Requests\Article\ArticleStatusRequest;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\UserLikeArticle;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function PHPUnit\Framework\fileExists;
//use Illuminate\Support\Facades\File as FacadesFile;

class ArticleController extends Controller
{
    public function index(ArticleFilterRequest $request)
    {
        $users = User::all();
        $categories = Category::all();

        $articles = Article::query()
            ->with(['category:id,name', 'user:id,name'])
            ->where(function ($q) use($request){
                if(!is_null($request))
                {
                    $q->orWhere('title', 'LIKE', '%' . $request->search_text . '%')
                    ->orWhere('slug', 'LIKE', '%' . $request->search_text . '%')
                    ->orWhere('body', 'LIKE', '%' . $request->search_text . '%')
                    ->orWhere('tags', 'LIKE', '%' . $request->search_text . '%');
                }

            })
            ->publishDate($request->publish_date)
            ->user($request->user_id)
            ->category($request->category_id)
            ->status($request->status)
            ->minViewCount($request->min_view_count)
            ->maxViewCount($request->max_view_count)
            ->minLikeCount($request->min_like_count)
            ->maxLikeCount($request->max_like_count)
            ->paginate(3);

        return view('admin.articles.list',
            ['articles' => $articles,
            'categories' => $categories,
            'users' => $users ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.articles.create-update', compact('categories'));
    }

    public function store(ArticleStoreRequest $request)
    {

        $data = $request->except("_token");

        $slug = $data['slug'] ?? $data['title'];
        $slug = Str::slug($slug);
        $slugTitle = Str::slug($data['title']);

        $checkSlug = $this->slugCheck($slug);

        if (!is_null($checkSlug))
        {
            $checkSlugTitle = $this->slugCheck($slugTitle);

            if (!is_null($checkSlugTitle))
            {
                $slug = Str::slug($slug . time());
            }
            else{
                $slug = $slugTitle;
            }
        }
        try {
            Article::create([
                'title' => $data['title'],
                'slug' => $slug,
                'body' =>  $data['body'],
//                'image' => !is_null($request->file('image')) ? $checkPath . $fileName : null,
                'image' => $request->image,
                'tags' => $data['tags'],
                'status' => $data['status'] ? 1 : 0,
                'view_count' => $data['view_count'],
                'like_count' => $data['like_count'],
                'read_time' => $data['read_time'],
                'publish_date' => $data['publish_date'],
                'seo_keywords' => $data['seo_keywords'],
                'seo_description' => $data['seo_description'],
                'user_id' => auth()->id(),
                'category_id' => $data['category_id']
            ]);
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }

        alert()->success('Başarılı', "Makale Kaydedildi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('article.index');
    }

    public function slugCheck(string $text)
    {
        return Article::query()->where('slug', $text)->first();
    }

    public function changeStatus(ArticleStatusRequest $request)
    {
        $articleID = $request->id;
        $article = Article::query()->where('id', $articleID)->first();

        $oldStatus = $article->status;
        $article->status = !$article->status;
        $article->save();

        $statusText = ($oldStatus == 1 ? 'Aktif' : 'Pasif') . "'ten" . ($article->status == 1 ? ' Aktif' : ' Pasif');
        alert()->success('Başarılı', $article->title . ' status ' . $statusText . ' olarak güncellendi')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->back();
    }

    public function delete(ArticleStatusRequest $request)
    {
        $articleID = $request->id;
        Article::query()->where('id', $articleID)->first()->delete();

        alert()->success('Başarılı', 'Makale Silindi')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->back();
    }

    public function edit(Request $request, int $articleID)
    {
//        dd($articleID);
        $categories = Category::all();

        $article = Article::query()->where('id', $articleID)->first();

        if (is_null($article))
        {
            alert()->error('Hata', 'Makale bulunamadı')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
            return redirect()->back();
        }
        return view('admin.articles.create-update', compact('article','categories'));
    }

    public function update(ArticleUpdateRequest $request, int $articleID)
    {
        $article = Article::query()->where('id', $articleID)->first();

        $articleQuery = Article::query()->where('id', $articleID);

        $articleFind = $articleQuery->first();

        $slug = $articleFind->title != $request->title ? $request->title : ($request->slug ?? $request->title);
        $slug = Str::slug($slug);
        $slugTitle = Str::slug($request->title);

        if ($articleFind->slug != $slug)
        {
            $checkSlug = $this->slugCheck($slug);
            if(!is_null($checkSlug))
            {
                $checkTitleSlug = $this->slugCheck($slugTitle);
                if (!is_null($checkTitleSlug))
                {
                    $slug = Str::slug($slug . time());
                }
                else
                {
                    $slug = $slugTitle;
                }
            }
        }
        else
        {
            unset($request->slug);
        }
        $data = [
            'title' => $request->title,
            'slug' => $slug,
            'body' => $request->body,
            'image' => !is_null($request->image) ? $request->image : $article->image,
            'tags' => $request->tags,
            'status' => $request->status ? 1 : 0,
            'view_count' => $request->view_count,
            'like_count' => $request->like_count,
            'read_time' => $request->read_time,
            'publish_date' => $request->publish_date,
            'seo_keywords' => $request->seo_keywords,
            'seo_description' => $request->seo_description,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id
        ];

//        $articleOld = $articleQuery->first();

        try {
            $articleQuery->first()->update($data);

//            if (!is_null($request->image))
//            {
//                if (file_exists(public_path($articleOld->image)))
//                {
//                    \Illuminate\Support\Facades\File::delete(public_path($articleOld->image));
//                }
//                $imageFile->storeAs($folder,  $fileName, 'public');
//            }
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Makale güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route("article.index");
    }

    public function favorite(Request $request): \Illuminate\Http\JsonResponse
    {
        $article = Article::query()->with(['articleLikes' => function($query)
        {
            $query->where('user_id', auth()->id());
        }
        ])->where('id', $request->id)->firstOrFail();

        if ($article->articleLikes->count())
        {
            $article->articleLikes()->delete();
            $article->like_count--;
            $process = 0;
        }
        else
        {
            UserLikeArticle::create([
                'user_id' => auth()->id(),
                'article_id' => $article->id
            ]);
            $article->like_count++;
            $process = 1;
        }

        $article->save();

        return response()
            ->json(['status' => "success", "message" => "Başarılı", "like_count" => $article->like_count, "process" => $process])
            ->setStatusCode(200);
    }
}
