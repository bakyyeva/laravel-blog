<?php

namespace App\Http\Middleware;

use App\Models\Article;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitedArticleMiddleware
{

    public function __construct(public Article $article)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      dd($article);
        $visitedArticles = session()->get('visited_articles', []);
        $visitedArticles[] = $article->id;
        session()->put('visited_articles', $visitedArticles);
        session()->put('last_article', $article);

        return $next($request);
    }
}
