<?php
        
namespace App\Http\Interfaces;     
interface ProfileInterface
{                    
    public function Profile();     
    public function UpdateProfile($data = []);
    public function UpdatePassword($data);
    public function Logout();
    
                    
}