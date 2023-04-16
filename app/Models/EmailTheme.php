<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTheme extends Model
{
    protected $guarded = [];

    public const THEME_TYPE = [
        'Tema Türü Seçiniz',
        'Kendim İçerik Oluşturmak İstiyorum',
        'Parola Sıfırlama Maili'
    ];

    public const PROCESS = [
        'İşlem Seçin',
        'Email Doğrulama Mail İçeriği',
        'Parola Sıfırlama Mail İçeriği',
        'Parola Sıfırlama İşlemi Tamamlandığında Gönderilecek Mail İçeriği'
    ];

    public function getThemeTypeAttribute($value):string
    {
        return self::THEME_TYPE[$value];
    }

    public function getProcessAttribute($value):string
    {
        return self::PROCESS[$value];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeThemeType($q, $theme_type)
    {
        if (!is_null($theme_type))
            return $q->where('theme_type', $theme_type);
    }

    public function scopeProcess($q, $process)
    {
        if (!is_null($process))
            return $q->where('process', $process);
    }

    public function scopeSearchText($q, $search_text)
    {
        if (!is_null($search_text))
            return $q->orWhere('body', 'LIKE', '%' . $search_text . '%');
    }
}
