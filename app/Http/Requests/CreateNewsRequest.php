<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.type' => 'required|in:news',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string',
            'data.attributes.news' => 'required|string',
            'data.attributes.image' => 'required|string',
            'data.attributes.list1' => 'required|string',
        ];
    }
}
