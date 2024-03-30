<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePostWithoutImageRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public static function rules()
    {
        return [
            'id' => 'required',
            'title' => 'required',
            'body' => 'required',
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if ($language == 'ar') {
            return [
                'id.required' => 'حقل المعرف مطلوب.',
                'title.required' => 'حقل العنوان مطلوب.',
                'body.required' => 'حقل المحتوي مطلوب.',
            ];
        } else {
            return [
                'id.required' => 'The ID field is required.',
                'title.required' => 'The title field is required.',
                'body.required' => 'The body field is required.',
            ];
        }
    }
}
