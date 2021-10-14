<?php

namespace App\Modules\Comments\Rules;

use App\Modules\Comments\Models\CommentModel;
use App\Modules\Comments\Repositories\CommentsRepository;
use Illuminate\Contracts\Validation\Rule;

/**
 * Check that comment available for update and delete operations by time.
 * For that comment creation time must be within the allotted time.
 */
class EditAndDeleteInTimeRule implements Rule
{
    private const SECONDS = 3600; // one hour

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

        return $comment->created_at->timestamp + CommentModel::SECONDS_TO_CHANGE >= time();
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return "You can delete or edit a comment within an hour after creating.";
    }
}
