<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'logo' => ['image', 'max:2048', 'nullable'],
            'category_default_image' => ['image', 'max:2048', 'nullable'],
            'article_default_image' => ['image', 'max:2048', 'nullable'],
            'default_comment_profile_image' => ['image', 'max:2048', 'nullable'],
            'reset_password_image' => ['image', 'max:2048', 'nullable'],
            'header_text' => ['max:255', 'nullable'],
            'footer_text' => ['max:255', 'nullable'],
            'telegram_link' => ['max:255', 'nullable'],
            'feature_categories_is_active' => 'boolean',
            'video_is_active' => 'boolean',
            'author_is_active' => 'boolean',

        ];
    }
}
