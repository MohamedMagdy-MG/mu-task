<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Classes\CommentRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\AddCommentRequest;
use App\Http\Requests\Comments\DeleteCommentRequest;
use App\Http\Requests\Comments\DeleteSelectedCommentsRequest;
use App\Http\Requests\Comments\UpdateCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CommentController extends Controller
{
    public $commentRepo;

    public function __construct()
    {
        $this->commentRepo = new CommentRepo();
    }

    public function index()
    {
        $id = null;
        if (array_key_exists('id', $_GET) && $_GET['id'] != null) {
            $id = $_GET['id'];
        }
        return $this->commentRepo->All($id);
        
    }

    public function Add(Request $request)
    {

        $validator = Validator::make($request->only(['comment', 'post_id']), AddCommentRequest::rules(), AddCommentRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Add comment Operation Failed' : $message = 'فشلت عملية اضافة التعليق';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->commentRepo->Add($request->only(['comment', 'post_id']));
        }
    }
    public function Update(Request $request)
    {
        $validator = Validator::make($request->only(['id', 'comment', 'post_id']), UpdateCommentRequest::rules(), UpdateCommentRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Edit comment Operation Failed' : $message = 'فشلت عملية تعديل التعليق';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->commentRepo->Update($request->only(['id', 'comment', 'post_id']));
        }
    }

    public function Delete(Request $request)
    {
        $validator = Validator::make($request->only(['id']), DeleteCommentRequest::rules(), DeleteCommentRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Delete comment Operation Failed' : $message = 'فشلت عملية حذف التعليق';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->commentRepo->Delete($request->only(['id']));
        }
    }
    public function DeleteSelected(Request $request)
    {
        $validator = Validator::make($request->only(['ids','post_id']), DeleteSelectedCommentsRequest::rules(), DeleteSelectedCommentsRequest::Message());
        if ($validator->fails()) {
            request()->headers->has('language') ? $language = request()->headers->get('language') : $language = 'en';
            $language == 'en' ? $message = 'Delete comments Operation Failed' : $message = 'فشلت عملية حذف التعليقات';
            return Helper::ResponseData(null, $message, false, 400, $validator->errors());
        } else {
            return $this->commentRepo->DeleteSelected($request->only(['ids','post_id']));
        }
    }
}
