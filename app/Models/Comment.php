<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ['user:id,name', 'article', 'parentComment'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function article(): HasOne
    {
        return $this->hasOne(Article::class, 'id', 'article_id');
    }
    public function parentComment(): HasOne
    {
        return $this->hasOne(Comment::class, 'id', 'parent_id');
    }

    public function scopeComment($query, $comment)
    {
        if (!is_null($comment))
            $query->where('comments', 'LIKE', '%' . $comment . '%');
    }

    public function scopeStatus($q, $status)
    {
        if (!is_null($status))
            return $q->where('status', $status);
    }

    public function scopeUser($q, $user_id)
    {
        if (!is_null($user_id))
            return $q->where('user_id', $user_id);
    }

    public function scopeArticle($q, $article_id)
    {
        if (!is_null($article_id))
            return $q->where('article_id', $article_id);
    }

}
