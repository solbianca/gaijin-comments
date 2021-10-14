<?php

namespace App\Modules\Comments\Rules;

use App\Modules\Comments\Repositories\CommentsRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CommentBelongToUserRule implements Rule
{
    private CommentsRepository $commentsRepository;

    /**
     * @param CommentsRepository $commentsRepository
     */
    public function __construct(CommentsRepository $commentsRepository)
    {
        $this->commentsRepository = $commentsRepository;
    }

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        $comment = $this->commentsRepository->getOne((int)$value);

        return $comment->user_id === Auth::id();
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return "Comment don't belong to user";
    }
}
