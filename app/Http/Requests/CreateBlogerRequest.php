<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBlogerRequest extends FormRequest
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
            'data.type' => 'required|in:blogers',
            'data.attributes' => 'required|array',
            'data.attributes.login' => 'required|string',
            'data.attributes.password' => 'required|string',
            'data.attributes.status' => 'required|string',
        ];
    }
}
