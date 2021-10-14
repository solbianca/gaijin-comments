<?php

namespace App\Http\Controllers;

use App\Modules\Comments\Services\CommentsService;
use Illuminate\Contracts\View\View;

class MainPageController extends Controller
{
    private CommentsService $commentsService;

    /**
     * @param CommentsService $commentsService
     */
    public function __construct(CommentsService $commentsService)
    {
        $this->commentsService = $commentsService;
    }

    public function __invoke()
    {
        $comments = $this->commentsService->getAllAsTree();

        return $this->view('main', ['nodes' => $comments]);
    }
}
