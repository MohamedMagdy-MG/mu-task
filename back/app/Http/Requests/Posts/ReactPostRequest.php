<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReactPostRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public static function rules()
    {
        return [
            'id' => 'required',
            'reaction' => 'nullable|in:like,love'
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if ($language == 'ar') {
            return [
                'id.required' => 'حقل المعرف مطلوب.',
                'reaction.in' => 'حقل التفاعل غير صالح.',
            ];
        } else {
            return [
                'id.required' => 'The ID field is required.',
                'reaction.in' => 'The reaction field is invalid.',
            ];
        }
    }
}
