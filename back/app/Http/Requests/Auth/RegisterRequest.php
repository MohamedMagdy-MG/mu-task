<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    
    public function authorize()
    {
        return false;
    }


    public static function rules()
    {
        return [
            'name_en' => 'required',
            'name_ar' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'national_id' => 'required|unique:users,national_id|regex:/1([\d]{9})/',
            'password' => 'required|min:10|max:30|regex:/(?=.*[a-zA-Z])(?=.*[0-9])/|confirmed',            
            'password_confirmation' => 'required',            
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if($language == 'ar'){
            return [
                'name_en.required' => 'حقل الاسم (باللغة الانجليزية) مطلوب.',
                'name_ar.required' => 'حقل الاسم (باللغة العربية) مطلوب.',
                'name_ar.min' => 'يجب ألا يقل الاسم (باللغة العربية) عن 8 أحرف.',
                'name_ar.max' => 'يجب ألا يزيد الاسم (باللغة العربية) عن 30 حرفًا.',
                'email.required' => 'حقل البريد الإلكتروني مطلوب.',
                'email.email' => 'يجب أن يكون البريد الإلكتروني عنوان بريد إلكتروني صالحًا.',
                'email.unique' => 'تم أخذ البريد الإلكتروني بالفعل.',
                'national_id.required' => 'حقل الهوية الوطنية مطلوب.',
                'national_id.unique' => 'تم أخذ البريد الإلكتروني بالفعل.',
                'national_id.regex' => 'يجب أن تكون الهوية الوطنية صحيحة.',
                'password.regex' => 'تنسيق كلمة المرور غير صالح (يجب أن يحتوي على الأقل على حرف خاص واحد وحرف صغير وحرف كبير ورقم)',
                'password.required' => 'حقل كلمة المرور مطلوب.',
                'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
                'password.max' => 'يجب ألا تزبد كلمة المرور عن 30 حرفًا.',
                'password.confirmed' => 'يجب أن تتطابق كلمة المرور وتاكيد كلمة المرور.',
                'password_confirmation.required' => 'حقل تاكيد كلمة المرور مطلوب.',

            ];
        }else{
            return [
                'name_en.required' => 'The Name (English) field is required.',
                'name_ar.required' => 'The Name (Arabic) field is required.',
                'name_ar.min' => 'The Name (Arabic) must be at least 8 Characters.',
                'name_ar.max' => 'The Name (Arabic) must not be greater than 30 Characters.',
                'email.required' => 'The Email Address field is required.',
                'email.email' => 'The Email Address must be a valid email address.',
                'email.unique' => 'The Email Address has already been taken.',
                'national_id.required' => 'The National ID field is required.',
                'national_id.regex' => 'The National ID must be a valid.',
                'national_id.unique' => 'The National ID has already been taken.',
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
