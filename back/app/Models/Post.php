<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory, HasUuids ,SoftDeletes;

    protected $fillable = [
        "user_id",
        "title",
        "body",
        "image"
    ];


    public function User(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Comments(){
        return $this->hasMany(Comment::class, 'post_id')->latest();
    }

    public function Reactions(){
        return $this->hasMany(Reacted::class, 'post_id');
    }

    public function isAuthReact(){
        return $this->hasOne(Reacted::class, 'post_id')->where("user_id",Auth::guard('api')->user()->id);
    }
    public function Mine(){
        return $this->user_id == Auth::guard('api')->user()->id ? true : false;
    }



}
