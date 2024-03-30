<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'You Must Login First!' : $message = 'يجب عليك تسجيل الدخول أولا!';
        abort(Helper::ResponseData(null,$message,false,401));
    }
}
