<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteSelectedCommentsRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public static function rules()
    {
        return [
            'ids' => 'required|array',
            'post_id' => 'required' 
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if ($language == 'ar') {
            return [
                'ids.required' => 'حقل معرف التعليقات مطلوب.',
                'ids.array' => 'حقل معرف التعليقات خطأ.',
                'post_id.required' => 'حقل معرف المقال مطلوب.',
            ];
        } else {
            return [
                'ids.required' => 'The comments ids field is required.',
                'ids.array' => 'The comments ids field is invalid.',
                'post_id.required' => 'The post id field is required.',
            ];
        }
    }
}
