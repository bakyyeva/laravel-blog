<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
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
            'name' => 'required',
            'username' => ['required', 'unique:users'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password' => ['required', Password::min(8)->symbols()->mixedCase()->letters()->numbers()],
            'about' => 'nullable',
//            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
//            'image' => ['image', 'max:2048', 'nullable'],
        ];
    }
}
