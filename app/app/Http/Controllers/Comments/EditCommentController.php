<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Modules\Comments\Exceptions\RecordNotExistsException;
use App\Modules\Comments\Repositories\CommentsRepository;
use App\Modules\Comments\Rules\CommentBelongToUserRule;
use App\Modules\Comments\Rules\EditAndDeleteInTimeRule;
use App\Modules\Comments\Services\CommentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EditCommentController extends Controller
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
     * Edit comment action handler.
     *
     * @param int $id Cimment id to delete.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws RecordNotExistsException
     */
    public function __invoke(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'comment-body' => 'required:string',
        ]);

        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'int',
                new CommentBelongToUserRule($this->commentsRepository),
                new EditAndDeleteInTimeRule($this->commentsRepository),
            ],
        ]);
        $validator->validate();

        return $this->view('comment', ['node' => $this->commentsService->edit($id, $validated['comment-body'])]);
    }
}
