<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCommentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public static function rules()
    {
        return [
            'id' => 'required',
            'comment' => 'required',
            'post_id' => 'required',
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if ($language == 'ar') {
            return [
                'id.required' => 'حقل المعرف مطلوب.',
                'comment.required' => 'حقل التعليق مطلوب.',
                'post_id.required' => 'حقل معرف المقال مطلوب.',
            ];
        } else {
            return [
                'id.required' => 'The ID field is required.',
                'comment.required' => 'The comment field is required.',
                'post_id.required' => 'The post id field is required.',
            ];
        }
    }
}
