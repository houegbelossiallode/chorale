<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'citation' => ['nullable', 'string', 'max:500'],
            'date_naissance' => ['nullable', 'date'],
            'photo_url' => ['nullable', 'string', 'max:2048'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'hobbie' => ['nullable', 'string', 'max:255'],
            'activite' => ['nullable', 'string', 'max:255'],
            'love_choir' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
