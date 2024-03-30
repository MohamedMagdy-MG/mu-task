<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }


    public static function rules()
    {
        return [
            'name_en' => 'required|min:8|max:30',
            'name_ar' => 'required|min:8|max:30',
            'email' => 'required|email:rfc,dns|unique:users,email,'.Auth::guard('api')->user()->id,
            'national_id' => 'required|regex:/1([\d]{9})/|unique:users,national_id,'.Auth::guard('api')->user()->id,
            'image' => 'nullable|mimes:png,jpg',
        ];
    }

    public static function Message()
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        if($language == 'ar'){
            return [
                'name_en.required' => 'حقل الاسم (باللغة الانجليزية) مطلوب.',
                'name_en.min' => 'يجب ألا يقل الاسم (باللغة الانجليزية) عن 8 أحرف.',
                'name_en.max' => 'يجب ألا يزيد الاسم (باللغة الانجليزية) عن 30 حرفًا.',
                'name_ar.required' => 'حقل الاسم (باللغة العربية) مطلوب.',
                'name_ar.min' => 'يجب ألا يقل الاسم (باللغة العربية) عن 8 أحرف.',
                'name_ar.max' => 'يجب ألا يزيد الاسم (باللغة العربية) عن 30 حرفًا.',
                'email.required' => 'حقل البريد الإلكتروني مطلوب.',
                'email.email' => 'يجب أن يكون البريد الإلكتروني عنوان بريد إلكتروني صالحًا.',
                'email.unique' => 'تم أخذ البريد الإلكتروني بالفعل.',
                'national_id.required' => 'حقل الهوية الوطنية مطلوب.',
                'national_id.unique' => 'تم أخذ البريد الإلكتروني بالفعل.',
                'national_id.regex' => 'يجب أن تكون الهوية الوطنية صحيحة.',
                'image.mimes' => 'الصورة غير صالحة'
            ];
        }else{
            return [
                'name_en.required' => 'The Name (English) field is required.',
                'name_en.min' => 'The Name (English) must be at least 8 Characters.',
                'name_en.max' => 'The Name (English) must not be greater than 30 Characters.',
                'name_ar.required' => 'The Name (Arabic) field is required.',
                'name_ar.min' => 'The Name (Arabic) must be at least 8 Characters.',
                'name_ar.max' => 'The Name (Arabic) must not be greater than 30 Characters.',
                'email.required' => 'The Email Address field is required.',
                'email.email' => 'The Email Address must be a valid email address.',
                'email.unique' => 'The Email Address has already been taken.',
                'national_id.required' => 'The National ID field is required.',
                'national_id.email' => 'The National ID must be a valid.',
                'national_id.regex' => 'The National ID has already been taken.',
                'image.mimes' => 'The image is invalid'
            ];
        }
    }

   
}
