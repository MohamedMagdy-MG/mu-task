<?php
        
namespace App\Http\Interfaces;     
interface CommentInterface
{                    
    public function All($search);
    public function Add($data = []);
    public function Update($data = []);
    public function Delete($id);
    public function DeleteSelected($data);
    
                    
}