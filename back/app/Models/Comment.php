<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        "user_id",
        "post_id",
        "comment"
    ];

    public function User(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function Post(){
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function Mine(){
        return $this->user_id == Auth::guard('api')->user()->id ? true : false;
    }
}
