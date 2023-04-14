<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = ['created_at' => 'datetime'];

    public function getCreatedAtAttribute($value): string             //$value  - getCreatedAtAttribute ile created_at alanı aldık onun valuesi
    {
        return date("Y-m-d H:i", strtotime($value));
    }

    public function scopeName($query, $name)
    {
        if(!is_null($name))
            return $query->where('name', 'LIKE', '%' . $name . '%');
    }

    public function scopeSlug($query, $slug)
    {
        if(!is_null($slug))
            return $query->where('name', 'LIKE', '%' . $slug . '%');
    }

    public function scopeDescription($query, $description)
    {
        if(!is_null($description))
            return $query->where('name', 'LIKE', '%' . $description . '%');
    }

    public function scopeOrder($q, $order)
    {
        if (!is_null($order))
            return $q->where('order', $order);
    }

    public function scopeParentCategory($q, $parentID)
    {
        if (!is_null($parentID))
            return $q->where('parent_id', $parentID);
    }

    public function scopeUser($q, $userID)
    {
        if (!is_null($userID))
            return $q->where('user_id', $userID);
    }

    public function scopeStatus($query, $status)
    {
        if (!is_null($status))
            return $query->where("status",  $status);
    }

    public function scopeFeatureStatus($query, $status)
    {
        if (!is_null($status))
            return $query->where("feature_status", $status);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function parentCategory(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function articles():HasMany
    {
        return $this->hasMany(Article::class, "category_id", "id");
    }
    public function articlesActive():HasMany
    {
        return $this->hasMany(Article::class, "category_id", "id")
            ->where("status", 1)
            ->whereNotNull("publish_date")
            ->where("publish_date", "<=", now());
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
