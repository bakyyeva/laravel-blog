<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentFilterRequest extends FormRequest
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
            'comment' => ['max:30', 'nullable'],
            'min_like_count' => ['integer', 'nullable'],
            'max_like_count' => ['integer', 'nullable'],
            'min_unlike_count' => ['integer', 'nullable'],
            'max_unlike_count' => ['integer', 'nullable'],
        ];
    }
}
