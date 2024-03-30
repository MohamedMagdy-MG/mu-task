<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'data' => 'required|email',
        ];
    }
   
    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if($language == 'ar'){
            return [
                'data.required' => 'حقل البريد الإلكتروني مطلوب.',
                'data.email'=>'يجب أن يكون البريد الإلكتروني عنوان بريد إلكتروني صالحًا'

            ];
        }else{
            return [
                'data.required' => 'The Email field is required.',
                'data.email'=> 'The email must be a valid email address'

            ];
        }
    }
}
