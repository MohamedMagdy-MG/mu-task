<?php
        
namespace App\Http\Interfaces;     
interface PostInterface
{                    
    public function All($search);
    public function Add($data = []);
    public function Update($data = []);
    public function Delete($id);
    public function Find($id);
    
                    
}