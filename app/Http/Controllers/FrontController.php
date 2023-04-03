<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Category;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function home()
    {
        $settings = Settings::first();
        $categories = Category::query()->where("status", 1)->get();

        return view("front.index", compact("settings", "categories"));
    }

    public function category(Request $request, string $slug)
    {
        $settings = Settings::first();
        $categories = Category::query()->where("status", 1)->get();



//       $category = Category::query()->with('articles')->where('slug', $slug)->first();
        $category = Category::query()->with('articlesActive')->where('slug', $slug)->first();


//        $articles = $category->articlesActive()->with(['user', 'category'])->paginate(2);   //eager loading, with ile aldım user ile category

//        $articles = $category->articlesActive()->paginate(2);
//        $articles->load(['user', 'category']);                             //load 'da with gibi çalışır, sintaktikasy üytgeşik

        /*TEK SORGU İLE articles almak*/
        $articles = Article::query()
            ->with(['user:id,name,username', 'category:id,name'])
            ->whereHas('category', function ($query) use($slug){
                $query->where('slug', $slug);
//            })->get();
            })->paginate(2);

       return view('front.article-list', compact('category', 'categories', 'settings', 'articles'));

    }

//    public function articleDetail(Request $request, User $user, Article $article)
    public function articleDetail(Request $request, string $username, string $articleSlug)
    {
        $settings = Settings::first();
        $categories = Category::query()->where("status", 1)->get();

        $article = Article::query()->with([
            'user',
            'comments' => function($query) {
                 $query->where('status', 1)
                     ->whereNull('parent_id');
            },
            'comments.user',
            'comments.children',
            'comments.children.user'
        ])
            ->where('slug', $articleSlug)
            ->first();

        return view('front.article-detail', compact('article', 'settings', 'categories'));

    }

    public function articleComment(Request $request, Article $article)
    {
        $data = $request->except("_token");
        if (Auth::check())
            $data['user_id'] = Auth::id();
        $data['article_id'] = $article->id;

        try {
            ArticleComment::create($data);
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Yorumunuz gönderilmiştir. Kontrol sonrası yayınlanacaktır.")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->back();
    }

}
