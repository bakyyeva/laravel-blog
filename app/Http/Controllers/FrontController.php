<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Category;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class FrontController extends Controller
{
    //*** View Share***
//    public function __construct()
//    {
//        $settings = Settings::first();
//        $categories = Category::query()->where("status", 1)->get();
//        View::share(["settings" => $settings, "categories" => $categories]);
//    }

    public function home()
    {
        $mostPopularArticles = Article::query()
            ->with(['user', 'category'])
            ->whereHas('user')
            ->whereHas('category')
            ->orderBy('view_count', 'DESC')
            ->limit(6)
            ->get();

        $lastPublishedArticles = Article::query()
            ->with(['user', 'category'])
            ->whereHas('user')
            ->whereHas('category')
            ->orderBy('publish_date', 'DESC')
            ->limit(6)
            ->get();

        return view("front.index", compact('mostPopularArticles', 'lastPublishedArticles'));
    }

    public function category(Request $request, string $slug)
    {
//       $category = Category::query()->with('articles')->where('slug', $slug)->first();
        $category = Category::query()->with('articlesActive')->where('slug', $slug)->first();


//        $articles = $category->articlesActive()->with(['user', 'category'])->paginate(2);   //eager loading, with ile aldım user ile category

//        $articles = $category->articlesActive()->paginate(2);
//        $articles->load(['user', 'category']);                             //load 'da with gibi çalışır, sintaktikasy üytgeşik

        /*TEK SORGU İLE articles almak*/
        $articles = Article::query()
            ->with(['user:id,name,username', 'category:id,name,slug'])
            ->whereHas('category', function ($query) use($slug){
                $query->where('slug', $slug);
//            })->get();
            })->paginate(21);

       $title = Category::query()->where('slug', $slug)->first()->name . " Kategorisine Ait Makaleler.";
       return view('front.article-list', compact('category',  'articles', 'title'));

    }

//    public function articleDetail(Request $request, User $user, Article $article)
    public function articleDetail(Request $request, string $username, string $articleSlug)
    {
        $article = session()->get('last_article');
        $visitedArticles = session()->get('visited_articles');

        $visitedArticlesCategoryIds = [];
        $visitedArticlesAuthorIds = [];

        $visitedInfo = Article::query()
            ->select('category_id', 'user_id')
            ->whereIn('id', $visitedArticles)
            ->get();

        foreach ($visitedInfo as $item)
        {
            $visitedArticlesCategoryIds[] = $item->category_id;
            $visitedArticlesAuthorIds[] = $item->user_id;
        }

        $suggestArticles = Article::query()
            ->with(['user', 'category'])
            ->where(function ($query) use ($visitedArticlesCategoryIds, $visitedArticlesAuthorIds){
                $query->whereIn('category_id', $visitedArticlesCategoryIds)
                    ->orWhereIn('user_id', $visitedArticlesAuthorIds);
            })
            ->whereNotIn('id', $visitedArticles)
            ->limit(6)
            ->get();


        $userLike = $article
            ->articleLikes
            ->where("article_id", $article->id)
            ->where("user_id", \auth()->id())
            ->first();

        $article->increment('view_count');
        $article->save();

        return view('front.article-detail', compact('article', 'userLike', 'suggestArticles'));

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

    public function authorArticles(Request $request, string $username)
    {
        $articles = Article::query()
            ->with(['user:id,name,username', 'category:id,name,slug'])
            ->whereHas('user', function ($query) use($username){
                $query->where('username', $username);
            })->paginate(21);

        $title = User::query()->where('username', $username)->first()->name . " Makaleleri";
        return view('front.article-list', compact('articles', 'title'));
    }

    public function search(Request $request)
    {
        $searchText = $request->q;

        $articles = Article::query()
            ->with(['user', 'category'])
            ->whereHas('user', function ($query) use ($searchText){
                $query->where('name', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('username', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('about', 'LIKE', '%' . $searchText . '%');
            })
            ->whereHas('category', function ($query) use ($searchText){
                $query->orWhere('name', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('slug', 'LIKE', '%' . $searchText . '%');
            })
            ->orWhere('title', 'LIKE', '%' . $searchText . '%')
            ->orWhere('slug', 'LIKE', '%' . $searchText . '%')
            ->orWhere('body', 'LIKE', '%' . $searchText . '%')
            ->orWhere('tags', 'LIKE', '%' . $searchText . '%')
            ->paginate(30);

        $title = $searchText . " Arama Sonucu";
        return view('front.article-list', compact('articles', 'title'));
    }

    public function articleList()
    {
        $articles = Article::query()->orderBy('publish_date', 'DESC')->paginate(21);

        return view('front.article-list', compact('articles'));
    }

}
