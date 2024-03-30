<?php

namespace App\Http\Classes;

use App\Helpers\Helper;
use App\Http\Interfaces\AuthInterface;
use App\Models\User;
use App\Notifications\SendForgetOtpNotification;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AuthRepo implements AuthInterface
{
    public $user;
    public function __construct()
    {
        $this->user = new User();
    }
    public function Register($data = [])
    {
        $user = $this->user->create($data);
        $token = Auth::guard('api')->login($user);
        $user->update([
            'otp' => rand(10000, 99999),
            'otp_date' => Carbon::now()
        ]);
        Notification::send($user, new SendOtpNotification());

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Register Operation Success' : $message = 'نجحت عملية التسجيل';
        return Helper::ResponseData([
            'id' => Auth::guard('api')->user()->id,
            'name' => $language == 'ar' ? Auth::guard('api')->user()->name_ar : Auth::guard('api')->user()->name_en,
            'name_en' => Auth::guard('api')->user()->name_en,
            'name_ar' => Auth::guard('api')->user()->name_ar,
            'email' => Auth::guard('api')->user()->email,
            'national_id' => Auth::guard('api')->user()->national_id,
            'image' => Auth::guard('api')->user()->image != null ? env('APP_URL').Storage::url(Auth::guard('api')->user()->image) : null,
            'token' => $token,
            'otp_date' =>  Auth::guard('api')->user()->otp_date,
            'reset_otp_date' =>  Auth::guard('api')->user()->reset_otp_date,
        ], $message, true, 200);
    }

    public function ResendActivateCode($data)
    {
        $user = $this->user->where(function (Builder $query) use ($data) {
            $query->where('email', $data)->orWhere('national_id', $data);
        })->first();

        if (!$user) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'The reset code has expired' : $message = 'انتهت صلاحية رمز إعادة الضبط';
            return Helper::ResponseData(null, $message, false, 400, [
                'data' => [$message]
            ]);
        }
        $user->update([
            'otp' => rand(10000, 99999),
            'otp_date' => Carbon::now()
        ]);
        Notification::send($user, new SendOtpNotification());

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'The verification code has been re-sent' : $message = 'لقد تم اعادة ارسال رمز التحقق';
        return Helper::ResponseData(null, $message, true, 200);
    }

    public function ActiveAccount($data = [])
    {
        $user = $this->user->where(function (Builder $query) use ($data) {
            $query->where('email', $data['data'])->orWhere('national_id', $data['data']);
        })->where('otp', $data['otp'])->first();

        if (!$user) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'The code has expired' : $message = 'انتهت صلاحية رمز التحقق';
            return Helper::ResponseData(null, $message, false, 400, [
                'data' => [$message]
            ]);
        }

        $user->update([
            'otp' => null,
            'otp_date' => null,
            'email_verified_at' => Carbon::now(),
        ]);

        $token = Auth::guard('api')->login($user);

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'The account has been activated successfully' : $message = 'لقد تم تفعيل الحساب بنجاح';
        return Helper::ResponseData([
            'id' => $user->id,
            'name' => $language == 'ar' ? $user->name_ar : $user->name_en,
            'name_en' => $user->name_en,
            'name_ar' => $user->name_ar,
            'email' => $user->email,
            'national_id' => $user->national_id,
            'image' => $user->image != null ? env('APP_URL').Storage::url($user->image) : null,
            'token' => $token,
            'otp_date' =>  $user->otp_date,
            'reset_otp_date' =>  $user->reset_otp_date,
        ], $message, true, 200);
    }



    public function Login($data = [])
    {
        $email = $this->user->where('national_id', $data['data'])->orWhere('email', $data['data'])->select('email')->first();
        $credentials = [
            'email' => $email,
            'password' => $data['password']
        ];
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Invalid Credential' : $message = 'بيانات غير صالحة';
            return Helper::ResponseData(null, $message, false, 401);
        }

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Login Operation Success' : $message = 'نجحت عملية تسجيل الدخول';
        return Helper::ResponseData([
            'id' => Auth::guard('api')->user()->id,
            'name' => $language == 'ar' ? Auth::guard('api')->user()->name_ar : Auth::guard('api')->user()->name_en,
            'name_en' => Auth::guard('api')->user()->name_en,
            'name_ar' => Auth::guard('api')->user()->name_ar,
            'email' => Auth::guard('api')->user()->email,
            'national_id' => Auth::guard('api')->user()->national_id,
            'image' => Auth::guard('api')->user()->image != null ? env('APP_URL').Storage::url(Auth::guard('api')->user()->image) : null,
            'token' => $token,
            'otp_date' =>  Auth::guard('api')->user()->otp_date,
            'reset_otp_date' =>  Auth::guard('api')->user()->reset_otp_date,
        ], $message, true, 200);
    }

    public function ForgetPassword($data = [])
    {
        $user = $this->user->where('national_id', $data)->orWhere('email',$data)->first();
        if (!$user) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Failed to find user' : $message = 'فشل العثور علي المستخدم';
            return Helper::ResponseData(null, $message, false, 404);
        }
        $user->update([
            'reset_otp' => rand(10000, 99999),
            'reset_otp_date' => Carbon::now()
        ]);

        Notification::send($user, new SendForgetOtpNotification());


        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Send OTP Operation Success' : $message = 'نجحت عملية ارسال رمز الاستعادة';
        return Helper::ResponseData(null, $message, true, 200);
    }

    public function PasswordRecovery($data = [])
    {
        $user = $this->user->where(function (Builder $query) use ($data) {
            $query->where('email', $data['data'])->orWhere('national_id', $data['data']);
        })->where('reset_otp', $data['otp'])->first();

        if (!$user) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'The reset code has expired' : $message = 'انتهت صلاحية رمز إعادة الضبط';
            return Helper::ResponseData(null, $message, false, 400, [
                'data' => [$message]
            ]);
        }

        $user->update([
            'reset_otp' => null,
            'reset_otp_date' => null,
        ]);

        $token = Auth::guard('api')->login($user);

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'The account has been activated successfully' : $message = 'لقد تم تفعيل الحساب بنجاح';
        return Helper::ResponseData([
            'id' => $user->id,
            'name' => $language == 'ar' ? $user->name_ar : $user->name_en,
            'name_en' => $user->name_en,
            'name_ar' => $user->name_ar,
            'email' => $user->email,
            'national_id' => $user->national_id,
            'image' => $user->image != null ? env('APP_URL').Storage::url($user->image) : null,
            'token' => $token,
            'otp_date' =>  $user->otp_date,
            'reset_otp_date' =>  $user->reset_otp_date,
        ], $message, true, 200);
    }

    public function ResendPasswordRecoveryCode($data)
    {
        $user = $this->user->where(function (Builder $query) use ($data) {
            $query->where('email', $data)->orWhere('national_id', $data);
        })->first();

        if (!$user) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'The code has expired' : $message = 'انتهت صلاحية رمز التحقق';
            return Helper::ResponseData(null, $message, false, 400, [
                'data' => [$message]
            ]);
        }
        $user->update([
            'reset_otp' => rand(10000, 99999),
            'reset_otp_date' => Carbon::now()
        ]);
        Notification::send($user, new SendForgetOtpNotification());

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'The verification code has been re-sent' : $message = 'لقد تم اعادة ارسال رمز التحقق';
        return Helper::ResponseData(null, $message, true, 200);
    }

    


}
