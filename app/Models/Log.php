<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Log extends Model
{
    protected $guarded = [];

    protected $casts = ['data' => 'array'];

    protected $dateFormat = "Y-m-d H:i:s";

    public CONST ACTIONS = [
        'create',
        'update',
        'delete',
        'force delete',
        'restore',
        'login',
        'logout',
        'verify user',
        'password reset mail send'
    ];

    public CONST MODELS = [
        Article::class,
        Category::class,
        User::class,
        Settings::class
    ];

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
