<?php

namespace App\Http\Classes;

use App\Helpers\Helper;
use App\Http\Interfaces\PostInterface;
use App\Http\Resources\PostsResources;
use App\Http\Resources\PostsWithCommentsResources;
use App\Models\Post;
use App\Models\Reacted;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostRepo implements PostInterface
{

    public $post;
    public $reacted;
    public function __construct()
    {
        $this->post = new Post();
        $this->reacted = new Reacted();
    }

    public function All($search)
    {

        $posts = $this->post;
        if ($search != null) {
            $posts = $posts
                ->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('body', 'LIKE', '%' . $search . '%')
                ->orWhereHas('User', function (Builder $query) use ($search) {
                    $query->where('name_en', 'LIKE', '%' . $search . '%')
                        ->orWhere('name_ar', 'LIKE', '%' . $search . '%')
                        ->orWhere('national_id', 'LIKE', '%' . $search . '%');
                });
        }
        $posts = $posts->latest()->paginate(10);
        $data = [
            'Posts' => PostsResources::collection($posts),
            'Pagination' => [
                'total'       => $posts->total(),
                'count'       => $posts->count(),
                'perPage'     => $posts->perPage(),
                'currentPage' => $posts->currentPage(),
                'totalPages'  => $posts->lastPage()
            ]
        ];
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Get All posts Operation Success' : $message = 'نجحت عملية الحصول علي كل المقالات ';

        return Helper::ResponseData($data, $message, true, 200);
    }

    public function Add($data = [])
    {
        if (isset($data['image'])) {
            if (is_file($data['image'])) {
                if (Auth::guard('api')->user()->image != null) {
                    Storage::disk('public')->delete(Auth::guard('api')->user()->image);
                }
                $path =  Storage::disk('public')->put('/media/posts/' . Auth::guard('api')->user()->id . '-' . time(), $data['image']);
                $data['image'] = $path;
            } else {
                unset($data['image']);
            }
        }

        $data['body'] = utf8_encode($data['body']);
        $data['title'] = utf8_encode($data['title']);
        $data['user_id'] = Auth::guard('api')->user()->id;
        $this->post->create($data);
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Add post Operation Success' : $message = 'نجحت عملية اضافة المقال';

        return Helper::ResponseData(null, $message, true, 200);
    }

    public function Update($data = [])
    {

        $post = $this->post->where('id', $data['id'])->where('user_id', Auth()->guard('api')->user()->id)->first();
        if (!$post) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'post Not Found' : $message = 'المقال غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }

        if (isset($data['image'])) {
            if (is_file($data['image'])) {
                if ($post->image != null) {
                    Storage::disk('public')->delete($post->image);
                }
                $path =  Storage::disk('public')->put('/media/posts/' . Auth::guard('api')->user()->id . '-' . time(), $data['image']);
                $data['image'] = $path;
            } else {
                unset($data['image']);
            }
        }else{
            if ($post->image != null) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = null;
        }
        $data['body'] = utf8_encode($data['body']);
        $data['title'] = utf8_encode($data['title']);



        $post->update($data);
        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Edit post Operation Success' : $message = 'نجحت عملية تعديل المقال';

        return Helper::ResponseData(null, $message, true, 200);
    }

    public function Delete($id)
    {

        $post = $this->post->where('id', $id)->where('user_id', Auth()->guard('api')->user()->id)->first();
        if (!$post) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'post Not Found' : $message = 'المقال غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }

        $post->delete();

        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'Delete post Operation Success' : $message = 'نجحت عملية حذف المقال';

        return Helper::ResponseData(null, $message, true, 200);
    }

    public function Find($id)
    {

        $post = $this->post->find($id);
        if (!$post) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'post Not Found' : $message = 'المقال غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }


        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'show post Operation Success' : $message = 'نجحت عملية عرض المقال';

        return Helper::ResponseData(new PostsWithCommentsResources($post), $message, true, 200);
    }

    public function React($data = [])
    {

        $post = $this->post->find($data['id']);
        if (!$post) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'post Not Found' : $message = 'المقال غير موجود';
            return Helper::ResponseData(null, $message, false, 404);
        }
        if (isset($data['reaction']) && $data['reaction'] != null) {
            $reacted = $this->reacted->where('post_id', $data['id'])->where('user_id', Auth()->guard('api')->user()->id)->first();
            if ($reacted) {
                $reacted->update([
                    "reaction" => $data['reaction']
                ]);
            } else {
                $this->reacted->create([
                    "user_id" => Auth()->guard('api')->user()->id,
                    "post_id" => $post->id,
                    "reaction" => $data['reaction']
                ]);
            }
        } else {
            $reacted = $this->reacted->where('post_id', $data['id'])->where('user_id', Auth()->guard('api')->user()->id)->first();
            if ($reacted) {
                $reacted->delete();
            }
        }


        request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
        $language == 'en' ? $message = 'reaction Operation Success' : $message = 'نجحت عملية التفاعل';

        return Helper::ResponseData( null, $message, true, 200);
    }
}
