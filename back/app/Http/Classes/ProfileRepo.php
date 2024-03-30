<?php

namespace App\Http\Classes;

use App\Helpers\Helper;
use App\Http\Interfaces\ProfileInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileRepo implements ProfileInterface
{

    public $user;
    public function __construct()
    {
        $this->user = new User();
    }

    public function Profile()
    {

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Successfully obtained profile data' : $message = 'تم الحصول على بيانات الملف الشخصي بنجاح';
        return Helper::ResponseData([
            'name' => $language == "ar" ? Auth::guard('api')->user()->name_en : Auth::guard('api')->user()->name_ar,
            'name_en' => Auth::guard('api')->user()->name_en,
            'name_ar' => Auth::guard('api')->user()->name_ar,
            'national_id' => Auth::guard('api')->user()->national_id,
            'image' => Auth::guard('api')->user()->image != null ? env('APP_URL') . Storage::url(Auth::guard('api')->user()->image) : null,
            'email' => Auth::guard('api')->user()->email,
        ], $message, true, 200);
    }

    public function UpdateProfile($data = [])
    {

        if (is_file($data['image'])) {
            if (Auth::guard('api')->user()->image != null) {
                Storage::disk('public')->delete(Auth::guard('api')->user()->image);
            }
            $path =  Storage::disk('public')->put('/media/users/' . Auth::guard('api')->user()->id, $data['image']);
            $data['image'] = $path;
        } else {
            unset($data['image']);
        }

        Auth::guard('api')->user()->update($data);
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Profile data has been updated successfully' : $message = 'تم تحديث بيانات الملف الشخصي بنجاح';

        $user = $this->user->find(Auth::guard('api')->user()->id);
        return Helper::ResponseData([
            'name' => $language == "ar" ? $user->name_en : $user->name_ar,
            'name_en' => $user->name_en,
            'name_ar' => $user->name_ar,
            'national_id' => $user->national_id,
            'image' => Auth::guard('api')->user()->image != null ? env('APP_URL') . Storage::url($user->image) : null,
            'email' => $user->email,
        ], $message, true, 200);
    }





    public function UpdatePassword($data)
    {

        Auth::guard('api')->user()->update([
            "password" => Hash::make($data),
        ]);

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = "The user's password has been updated successfully" : $message = 'تم تحديث كلمة مرور المستخدم بنجاح';
        return Helper::ResponseData(null, $message, true, 200);
    }

    public function Logout()
    {
        Auth::guard('api')->logout();
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'You have successfully logged out' : $message = 'تم تسجيل الخروج بنجاح';

        return Helper::ResponseData(null, $message, true, 200);
    }
}
