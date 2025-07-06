<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => 'required|string|max:1000',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'content' => ucfirst($this->content),
        ]);
    }
}