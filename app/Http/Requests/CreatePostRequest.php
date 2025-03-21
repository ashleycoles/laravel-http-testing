<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:70',
            'content' => 'required|string|min:50',
            'image' => 'nullable|string|url|max:255',
            'excerpt' => 'required|string|min:10|max:300',
            'author' => 'required|string|min:2|max:255',
        ];
    }
}
