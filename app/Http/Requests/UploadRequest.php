<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
            'uploadFile' => 'required|image|mimes:jpg,png,jpeg|max:5120',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return[
            'image' => ':attribute alanı resim olmalıdır.',

        ];
    }
}
