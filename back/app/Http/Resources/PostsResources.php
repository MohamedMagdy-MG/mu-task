<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;


class PostsResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function calculateDate($date)
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $carbon = new Carbon();
        $now = $carbon->now();
        $totalDuration = $now->diffInMinutes($date);

        if ($totalDuration == 0) {
            $language == 'ar' ?  $date = 'الان' : $date = "Just now";
        } elseif ($totalDuration < 60) {
            $language == 'ar' ?  $date = 'منذ ' . $totalDuration . " دقيفة" : $date = $totalDuration . " Minutes ago";
        } elseif ($totalDuration >= 60 && $totalDuration < 1440) {
            if (floor($totalDuration / 60) == 1) {
                $language == 'ar' ?  $date = "منذ  ساعة" : $date = floor($totalDuration / 60) . " Hour ago";
            } else if (floor($totalDuration / 60) == 2) {
                $language == 'ar' ?  $date = "منذ  ساعتين" : $date = "2 Hour ago";
            } else {
                $language == 'ar' ?  $date = 'منذ ' . floor($totalDuration / 60) . " ساعات" : $date = floor($totalDuration / 60) . " Hours ago";
            }
        } else {
            $transalte = new GoogleTranslate();
            $language == 'ar' ?  $date = $transalte->setSource('en')->setTarget('ar')->translate(date('d M Y - h:i A', strtotime($this->created_at))) :  $date = date('l, d M Y - h:i A', strtotime($this->created_at));
        }
        return $date;
    }
    public function toArray(Request $request): array
    {
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';

        return [
            "id" => $this->id,
            "User" => [
                "name" => $language == "ar" ? $this->User->name_ar : $this->User->name_en,
                "name_en" => $this->User->name_en,
                "name_ar" => $this->User->name_ar,
                "image" => $this->User->image,
                "mine" => $this->Mine()
            ],
            "Content" => [
                "title" => utf8_decode($this->title),
                "body" => utf8_decode($this->body),
                "image" => $this->image != null ? env('APP_URL') . Storage::url($this->image) : null,
                "time" => $this->calculateDate($this->created_at),
            ],

            "CommentsCount" => $this->Comments->count(),
            "LikeCount" => $this->Reactions->where('reaction', 'like')->count(),
            "LoveCount" =>  $this->Reactions->where('reaction', 'love')->count(),
            "isAuthReact" => $this->isAuthReact != null ? true : false,
            "AuthReact" => $this->isAuthReact != null ? $this->isAuthReact->reaction : null,
        ];
    }
}
