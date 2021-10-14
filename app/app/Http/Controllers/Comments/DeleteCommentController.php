<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Modules\Comments\Exceptions\RecordNotExistsException;
use App\Modules\Comments\Repositories\CommentsRepository;
use App\Modules\Comments\Rules\CommentBelongToUserRule;
use App\Modules\Comments\Rules\EditAndDeleteInTimeRule;
use App\Modules\Comments\Services\CommentsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeleteCommentController extends Controller
{
    private CommentsService $commentsService;

    private CommentsRepository $commentsRepository;

    public function __construct(CommentsService $commentsService, CommentsRepository $commentsRepository)
    {
        $this->commentsService = $commentsService;
        $this->commentsRepository = $commentsRepository;

        $this->middleware('auth');
    }

    /**
     * Delete comment action handler.
     *
     * @param int $id Cimment id to delete.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws RecordNotExistsException
     */
    public function __invoke($id)
    {
        Validator::make(['id' => $id], [
            'id' => [
                'required',
                'int',
                new CommentBelongToUserRule($this->commentsRepository),
                new EditAndDeleteInTimeRule($this->commentsRepository),
            ],
        ]);

        return $this->view('comment', ['node' => $this->commentsService->delete($id)]);
    }
}
