<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherPhotoRequest extends FormRequest
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
            'photo_x' => ['required', 'integer'],
            'photo_y' => ['required', 'integer'],
            'photo_width' => ['required', 'integer'],
            'photo_height' => ['required', 'integer'],
            'photo' => ['required', 'image', 'dimensions:min_width=200,min_height=200', 'min_resolve', 'crop_image_square']
        ];
    }
}
