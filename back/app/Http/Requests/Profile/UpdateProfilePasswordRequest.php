<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfilePasswordRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }


    public static function rules()
    {
        return [
            'password' => 'required|min:10|max:30|regex:/(?=.*[a-zA-Z])(?=.*[0-9])/|confirmed',            
            'password_confirmation' => 'required',            
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if($language == 'ar'){
            return [
                'password.regex' => 'تنسيق كلمة المرور غير صالح (يجب أن يحتوي على الأقل على حرف خاص واحد وحرف صغير وحرف كبير ورقم)',
                'password.required' => 'حقل كلمة المرور مطلوب.',
                'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
                'password.max' => 'يجب ألا تزبد كلمة المرور عن 30 حرفًا.',
                'password.confirmed' => 'يجب أن تتطابق كلمة المرور وتاكيد كلمة المرور.',
                'password_confirmation.required' => 'حقل تاكيد كلمة المرور مطلوب.',

            ];
        }else{
            return [
                'password.regex' => 'The Password format is invalid ( must contain at least one special character, a lowercase letter, an uppercase letter, and a number )',
                'password.required' => 'The Password field is required.',
                'password.min' => 'The Password must be at least 8 Characters.',
                'password.max' => 'The Password must not be greater than 30 Characters.',
                'password.confirmed' => 'The password must match and confirm the password.',
                'password_confirmation.required' => 'The Password Confirmation field is required.',

            ];
        }
    }

   
}
