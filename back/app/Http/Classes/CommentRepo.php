<?php

namespace App\Http\Classes;

use App\Helpers\Helper;
use App\Http\Interfaces\CommentInterface;
use App\Http\Resources\CommentsResources;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CommentRepo implements CommentInterface
{

    public $post;
    public $comment;
    public function __construct()
    {
        $this->post = new Post();
        $this->comment = new Comment();
    }

    public function All($post_id)
    {

        $post = $this->post->find($post_id);
        if (!$post) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'post Not Found' : $message = 'المقال غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }
        $comments = $this->comment->where('post_id', $post->id)->latest()->paginate(10);
        $data = [
            'Comments' => CommentsResources::collection($comments),
            'Pagination' => [
                'total'       => $comments->total(),
                'count'       => $comments->count(),
                'perPage'     => $comments->perPage(),
                'currentPage' => $comments->currentPage(),
                'totalPages'  => $comments->lastPage()
            ]
        ];
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Get All comments Operation Success' : $message = 'نجحت عملية الحصول علي كل التعلبقات ';

        return Helper::ResponseData($data, $message, true, 200);
    }

    public function Add($data = [])
    {

        $post = $this->post->find($data['post_id']);
        if (!$post) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'post Not Found' : $message = 'المقال غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }
        $data['user_id'] = Auth::guard('api')->user()->id;
        $data['comment'] = utf8_encode($data['comment']);

        $this->comment->create($data);
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'add comment Operation Success' : $message = 'نجحت عملية اضافة التعليق';
        return Helper::ResponseData(null, $message, true, 200);
    }

    public function Update($data = [])
    {

        $post = $this->post->find($data['post_id']);
        if (!$post) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'post Not Found' : $message = 'المقال غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }

        $comment = $this->comment->where('id', $data['id'])->where('user_id', Auth()->guard('api')->user()->id)->first();
        if (!$comment) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'comment Not Found' : $message = 'التعليق غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }
        $data['comment'] = utf8_encode($data['comment']);


        unset($data['post_id']);
        $post->update($data);
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Edit comment Operation Success' : $message = 'نجحت عملية تعديل التعليق';

        return Helper::ResponseData(null, $message, true, 200);
    }

    public function Delete($id)
    {

        $comment = $this->comment->where('id', $id)->where('user_id', Auth()->guard('api')->user()->id)->first();
        if (!$comment) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'comment Not Found' : $message = 'التعليق غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }

        $comment->delete();

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Delete comment Operation Success' : $message = 'نجحت عملية حذف التعليق';

        return Helper::ResponseData(null, $message, true, 200);
    }

    public function DeleteSelected($data)
    {

        $comments = $this->comment->whereIn('id', $data['ids'])->whereHas('Post', function(Builder $query) use($data){
            $query->where('id',$data['post_id'])->where('user_id', Auth()->guard('api')->user()->id);
        })->get();
        foreach ($comments as $comment) {
            $comment->delete();
        }

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Delete comments Operation Success' : $message = 'نجحت عملية حذف التعليقات';

        return Helper::ResponseData(null, $message, true, 200);
    }
}
