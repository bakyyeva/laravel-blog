<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleComment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function article(): HasOne
    {
        return $this->hasOne(Article::class, 'id', 'article_id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(ArticleComment::class, 'id', 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ArticleComment::class, 'parent_id', 'id');
    }

    public function scopeApproveStatus($query)
    {
        return $query->where('status', 0);
    }

    public function scopeStatus($query, $status)
    {
        if (!is_null($status))
        return $query->where('status', $status);
    }

    public function scopeUser($query, $userID)
    {
        if (!is_null($userID))
            return $query->where('user_id', $userID);
    }

    public function scopeCreatedDate($query, $createdDate)
    {
        if (!is_null($createdDate))
            return $query->where('created_at', '>=', $createdDate)
                ->where('created_at', '<=', \Illuminate\Support\Carbon::parse($createdDate)->addDays(1));
    }

    public function scopeSearchText($query, $searchText)
    {
        if (!is_null($searchText))
            return $query->where('name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('comments', 'LIKE', '%' . $searchText . '%');
    }


}
