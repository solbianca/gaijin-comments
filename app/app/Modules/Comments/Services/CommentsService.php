<?php

namespace App\Modules\Comments\Services;

use App\Modules\Comments\Dtos\CommentNode;
use App\Modules\Comments\Repositories\CommentsRepository;

/**
 * Responsible for providing methods for working with comments.
 */
class CommentsService
{
    private CommentsRepository $commentsRepository;

    private CommentsTreeBuilder $commentsTreeBuilder;

    public function __construct(CommentsRepository $commentsRepository, CommentsTreeBuilder $commentsTreeBuilder)
    {
        $this->commentsRepository = $commentsRepository;
        $this->commentsTreeBuilder = $commentsTreeBuilder;
    }

    /**
     * Create new comment.
     *
     * @param int $parentId
     * @param string $body
     * @param int $authorId
     * @return CommentNode
     * @throws \App\Modules\Comments\Exceptions\RecordNotExistsException
     */
    public function create(int $parentId, string $body, int $authorId): CommentNode
    {
        return new CommentNode($this->commentsRepository->create($parentId, $body, $authorId));
    }

    /**
     * Update comment body text.
     *
     * @param int $id
     * @param string $body
     * @return CommentNode
     * @throws \App\Modules\Comments\Exceptions\RecordNotExistsException
     */
    public function edit(int $id, string $body): CommentNode
    {
        return new CommentNode($this->commentsRepository->update($id, $body));
    }

    /**
     * Delete comment.
     *
     * @param int $id
     * @return CommentNode
     * @throws \App\Modules\Comments\Exceptions\RecordNotExistsException
     */
    public function delete(int $id): CommentNode
    {
        $this->commentsRepository->markAsDeleted($id);

        return new CommentNode($this->commentsRepository->getOne($id));
    }

    /**
     * Get all comments as tree.
     *
     * @return CommentNode[]
     */
    public function getAllAsTree(): array
    {
        $comments = $this->commentsRepository->getAllWithAuthor()->keyBy('id')->all();

        return $this->commentsTreeBuilder->build($comments);
    }
}
