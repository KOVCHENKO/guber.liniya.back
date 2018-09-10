<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\CommentRepository;
use Illuminate\Http\Request;
use App\src\Models\Comment;

class CommentController extends Controller
{

    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function create(Request $request)
    {
        return response($this->commentRepository->create($request->all()), 200);
    }

}