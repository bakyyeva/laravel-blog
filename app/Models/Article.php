<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getTagsAttribute(): array|false
    {
        return explode(",", $this->attributes['tags']);
    }

    public function scopePublishDate($q, $publish_date)
    {
        if(!is_null($publish_date))
        {
            $publish_date = Carbon::parse($publish_date)->format("Y-m-d");
            return $q->where('publish_date', $publish_date);
        }
    }
    public function scopeUser($q, $user_id)
    {
        if (!is_null($user_id))
            return $q->where('user_id', $user_id);
    }
    public function scopeCategory($q, $category_id)
    {
        if (!is_null($category_id))
            return $q->where('category_id', $category_id);
    }
    public function scopeStatus($q, $status)
    {
        if (!is_null($status))
            return $q->where('status', $status);
    }
    public function scopeMinViewCount($query, $min_view_count)
    {
        if (!is_null($min_view_count))
            return $query->where('view_count', '>=', $min_view_count);
    }
    public function scopeMaxViewCount($query, $max_view_count)
    {
        if (!is_null($max_view_count))
            return $query->where('view_count', '<=', $max_view_count);
    }
    public function scopeMinLikeCount($query, $min_like_count)
    {
        if (!is_null($min_like_count))
            return $query->where('like_count', '>=', $min_like_count);
    }
    public function scopeMaxLikeCount($query, $max_like_count)
    {
        if (!is_null($max_like_count))
            return $query->where('like_count', '<=', $max_like_count);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class, 'article_id', 'id');
    }
}
