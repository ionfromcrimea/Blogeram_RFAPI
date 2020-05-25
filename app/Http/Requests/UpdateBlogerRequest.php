<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogerRequest extends FormRequest
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
            'data.id' => 'required|string',
            'data.type' => 'required|in:blogers',
            'data.attributes' => 'sometimes|required|array',
            'data.attributes.login' => 'sometimes|required|string',
            'data.attributes.password' => 'sometimes|required|string',
            'data.attributes.status' => 'sometimes|required|string',
        ];
    }
}
