<?php

namespace App\Http\Requests\Log;

use Illuminate\Foundation\Http\FormRequest;

class LogFilterRequst extends FormRequest
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
            'process_id' => ['integer','nullable'],            //'integer',
            'process_type' => ['max:255', 'nullable'],
            'created_log' => ['date_format:Y-m-d H:i','nullable'],   //'date_format:Y-m-d H:i:s',
            'updated_log' => ['date_format:Y-m-d H:i','nullable']
        ];
    }
}
