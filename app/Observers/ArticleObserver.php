<?php

namespace App\Observers;

use App\Models\Article;
use App\Traits\Loggable;

class ArticleObserver
{
    use Loggable;
    /**
     * Handle the Article "created" event.
     */

    public function __construct()
    {
        $this->model = Article::class;
    }

    public function created(Article $article): void
    {
        $this->log('create', $article->id, $article->toArray(), $this->model);
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        $this->updateLog($article, $this->model);
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        $this->log('delete', $article->id, $article->toArray(), $this->model);
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        $this->log('restore', $article->id, $article->toArray(), $this->model);
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        $this->log('force delete', $article->id, $article->toArray(), $this->model);
    }

}
