<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Classes\PostRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\AddPostRequest;
use App\Http\Requests\Posts\DeletePostRequest;
use App\Http\Requests\Posts\ReactPostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Requests\Posts\UpdatePostWithoutImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    public $postRepo;

    public function __construct()
    {
        $this->postRepo = new PostRepo();
    }



    public function Index()
    {
        $search = null;
        if (array_key_exists('search', $_GET) && $_GET['search'] != null) {
            $search = $_GET['search'];
        }
        $id = null;
        if (array_key_exists('id', $_GET) && $_GET['id'] != null) {
            $id = $_GET['id'];
        }

        if ($id != null) {
            return $this->postRepo->Find($id);
        } else {
            return $this->postRepo->All($search);
        }
    }

    public function Add(Request $request)
    {

        $validator = Validator::make($request->only(['title', 'body', 'image']), AddPostRequest::rules(), AddPostRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Add post Operation Failed' : $message = 'فشلت عملية اضافة المقال';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->postRepo->Add($request->only(['title', 'body', 'image']));
        }
    }
    public function Update(Request $request)
    {
        $request->hasFile('image') ? $validator = Validator::make($request->only(['id', 'title', 'body', 'image']), UpdatePostRequest::rules(), UpdatePostRequest::Message()):$validator = Validator::make($request->only(['id', 'title', 'body', 'image']), UpdatePostWithoutImageRequest::rules(), UpdatePostWithoutImageRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Edit post Operation Failed' : $message = 'فشلت عملية تعديل المقال';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->postRepo->Update($request->only(['id', 'title', 'body', 'image']));
        }
    }

    public function Delete(Request $request)
    {
        $validator = Validator::make($request->only(['id']), DeletePostRequest::rules(), DeletePostRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Delete post Operation Failed' : $message = 'فشلت عملية حذف المقال';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->postRepo->Delete($request->only(['id']));
        }
    }
    public function React(Request $request)
    {
        $validator = Validator::make($request->only(['id', 'reaction']), ReactPostRequest::rules(), ReactPostRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'reaction Operation Failed' : $message = 'فشلت عملية التفاعل';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->postRepo->React($request->only(['id', 'reaction']));
        }
    }
}
