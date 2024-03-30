<?php
        
namespace App\Http\Interfaces;     
interface AuthInterface
{                    
    public function Register($data = []);     
    public function ResendActivateCode($data);
    public function ActiveAccount($data = []);
    public function Login($data = []);
    public function ForgetPassword($data = []);
    public function PasswordRecovery($data = []);
    public function ResendPasswordRecoveryCode($data);
    
                    
}