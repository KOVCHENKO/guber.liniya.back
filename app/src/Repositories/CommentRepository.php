<?php

namespace App\src\Repositories;

use App\src\Models\Comment;

class CommentRepository
{
    
    protected $comment;

    /**
     * CommentRepository constructor.
     * @param $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @param $comment
     * @return mixed
     * Создать комментария к заявке
     */
    public function create($comment)
    {
        return $this->comment->create($comment);
    }

}