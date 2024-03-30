<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddCommentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public static function rules()
    {
        return [
            'comment' => 'required',
            'post_id' => 'required',
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if ($language == 'ar') {
            return [
                'comment.required' => 'حقل التعليق مطلوب.',
                'post_id.required' => 'حقل معرف المقال مطلوب.',
            ];
        } else {
            return [
                'comment.required' => 'The comment field is required.',
                'post_id.required' => 'The post id field is required.',
            ];
        }
    }
}
