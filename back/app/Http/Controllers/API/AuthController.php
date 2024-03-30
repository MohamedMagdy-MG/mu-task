<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Classes\AuthRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ActiveAccountRequest;
use App\Http\Requests\Auth\LoginByNationalIDRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendVerificationCodeByNationalIDRequest;
use App\Http\Requests\Auth\SendVerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public $authRepo;
    public function __construct()
    {
        $this->authRepo = new AuthRepo();
    }

    public function Register(Request $request)
    {
        $validator = Validator::make($request->only(['name_en', 'name_ar', 'national_id', 'email', 'password', 'password_confirmation']), RegisterRequest::rules(), RegisterRequest::Message());

        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Registration failed' : $message = 'فشلت عملية التسجيل';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            $data = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'national_id' => $request->national_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ];
            return $this->authRepo->Register($data);
        }
    }
    

    public function ResendActivateCode(Request $request)
    {
        str_contains($request->data, '@') ? $validator = Validator::make($request->only(['data']), SendVerificationCodeRequest::rules(), SendVerificationCodeRequest::Message()) : $validator = Validator::make($request->only(['data']), SendVerificationCodeByNationalIDRequest::rules(), SendVerificationCodeByNationalIDRequest::Message());

        if($validator->fails()){
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Sending a activation code to the account failed' : $message = 'فشلت عملية إرسال رمز تنشيط الحساب';
            return Helper::ResponseData(null,$message,false,400,$validator->errors());
        }else{
            return $this->authRepo->ResendActivateCode($request->data);
        }   

    }
    public function ActiveAccount(Request $request)
    {
        $validator = Validator::make($request->only(['data','otp']), ActiveAccountRequest::rules(), ActiveAccountRequest::Message());

        if($validator->fails()){
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'active account failed' : $message = 'فشلت عملية تنشيط الحساب';
            return Helper::ResponseData(null,$message,false,400,$validator->errors());
        }else{
            $data = [
                "data" => $request->data,
                "otp" => $request->otp
            ];
            return $this->authRepo->ActiveAccount($data);
        }   

    }
    
    public function Login(Request $request)
    {   
        str_contains($request->data, '@')  ? $validator = Validator::make($request->only(['data', 'password']), LoginRequest::rules(), LoginRequest::Message()) : $validator = Validator::make($request->only(['data', 'password']), LoginByNationalIDRequest::rules(), LoginByNationalIDRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Login failed' : $message = 'فشلت عملية تسجيل الدخول';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            $data = [
                'data' => $request->data,
                'password' => $request->password,
            ];
            return $this->authRepo->Login($data);
        }
    }

    public function ForgetPassword(Request $request)
    {
        str_contains($request->data, '@') ? $validator = Validator::make($request->only(['data']), SendVerificationCodeRequest::rules(), SendVerificationCodeRequest::Message()) : $validator = Validator::make($request->only(['data']), SendVerificationCodeByNationalIDRequest::rules(), SendVerificationCodeByNationalIDRequest::Message());

        if($validator->fails()){
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Sending a verification code to the account failed' : $message = 'فشلت عملية إرسال رمز التحقق للحساب';
            return Helper::ResponseData(null,$message,false,400,$validator->errors());
        }else{
            return $this->authRepo->ForgetPassword($request->data);
        }   

    }

    public function PasswordRecovery(Request $request)
    {
        $validator = Validator::make($request->only(['data','otp']), ActiveAccountRequest::rules(), ActiveAccountRequest::Message());

        if($validator->fails()){
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Sending a recovery code to the account failed' : $message = 'فشلت عملية إرسال رمز استرجاع للحساب';
            return Helper::ResponseData(null,$message,false,400,$validator->errors());
        }else{
            $data = [
                "data" => $request->data,
                "otp" => $request->otp
            ];
            return $this->authRepo->PasswordRecovery($data);
        }   

    }

    public function ResendPasswordRecoveryCode(Request $request)
    {
        str_contains($request->data, '@') ? $validator = Validator::make($request->only(['data']), SendVerificationCodeRequest::rules(), SendVerificationCodeRequest::Message()) : $validator = Validator::make($request->only(['data']), SendVerificationCodeByNationalIDRequest::rules(), SendVerificationCodeByNationalIDRequest::Message());

        if($validator->fails()){
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Sending a recovery code to the account failed' : $message = 'فشلت عملية إرسال رمز استرجاع الحساب';
            return Helper::ResponseData(null,$message,false,400,$validator->errors());
        }else{
            return $this->authRepo->ResendPasswordRecoveryCode($request->data);
        }   

    }

    

    
}
