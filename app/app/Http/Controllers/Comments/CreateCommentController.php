<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Modules\Comments\Services\CommentsService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CreateCommentController extends Controller
{
    private CommentsService $commentsService;

    /**
     * @param CommentsService $commentsService
     */
    public function __construct(CommentsService $commentsService)
    {
        $this->commentsService = $commentsService;

        $this->middleware('auth');
    }

    /**
     * Create new comment action handler.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws ValidationException
     */
    public function __invoke(Request $request)
    {
        $validated = $this->validate($request, [
            'comment-body' => 'required:string',
            'parent-id' => 'required:integer',
        ]);

        $user = $request->user();

        $commentModel = $this->commentsService->create(
            (int)$validated['parent-id'],
            $validated['comment-body'],
            $user->id
        );

        return $this->view('comment', ['node' => $commentModel]);
    }
}
