<?php

namespace App\Http\Requests\Log;

use Illuminate\Foundation\Http\FormRequest;

class LogIdRequest extends FormRequest
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
//        dd($this->id);
        return [
            'id' => ['required', 'integer', 'exists:logs']
        ];
    }
}
