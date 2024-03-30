<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddPostRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public static function rules()
    {
        return [
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|mimes:png,jpg',
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if ($language == 'ar') {
            return [
                'title.required' => 'حقل العنوان مطلوب.',
                'body.required' => 'حقل المحتوي مطلوب.',
                'image.mimes' => 'الصورة غير صالحة'
            ];
        } else {
            return [
                'title.required' => 'The title field is required.',
                'body.required' => 'The body field is required.',
                'image.mimes' => 'The image is invalid'
            ];
        }
    }
}
