<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginByNationalIDRequest extends FormRequest
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
            'data' => 'required|regex:/1([\d]{9})/',
            'password' => 'required'
            
        ];
    }
   
    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if($language == 'ar'){
            return [
                'data.required' => 'حقل الهوية الوطنية مطلوب.',
                'data.regex' => 'يجب أن تكون الهوية الوطنية صحيحة.',
                'password.required' => 'حقل كلمة المرور مطلوب.',


            ];
        }else{
            return [
                'data.required' => 'The National ID field is required.',
                'data.regex' => 'The National ID has already been taken.',
                'password.required_if' => 'The Password field is required.',
            ];
        }
    }
}
