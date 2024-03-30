<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reacted extends Model
{
    // reaction   =>    like , love
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable =[
        "user_id",
        "post_id",
        "reaction"
    ];

    public function User(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function Post(){
        return $this->belongsTo(Post::class, 'post_id');
    }

}
