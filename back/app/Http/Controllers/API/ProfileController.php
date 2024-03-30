<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Classes\ProfileRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfilePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public $profileRepo;
    public function __construct()
    {
        $this->profileRepo = new ProfileRepo();
    }

    public function Profile()
    {
        return $this->profileRepo->Profile();
    }


    public function UpdateProfile(Request $request)
    {
        $validator = Validator::make($request->only(['name_en', 'name_ar', 'national_id', 'email', 'image']), UpdateProfileRequest::rules(), UpdateProfileRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Profile data has not been updated' : $message = 'لم يتم تحديث بيانات الملف الشخصي';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {

            $data = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'national_id' => $request->national_id,
                'email' => $request->email,
                'image' => $request->image,
            ];
            return $this->profileRepo->UpdateProfile($data);
        }
    }


    public function UpdatePassword(Request $request)
    {
        $validator = Validator::make($request->only(['password', 'password_confirmation']), UpdateProfilePasswordRequest::rules(), UpdateProfilePasswordRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Password not updated' : $message = 'لم يتم تحديث كلمة المرور ';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->profileRepo->UpdatePassword($request->password);
        }
    }

    public function Logout()
    {
        return $this->profileRepo->Logout();
    }
}
