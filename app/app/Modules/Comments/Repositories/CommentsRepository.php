<?php

namespace App\Modules\Comments\Repositories;

use App\Modules\Comments\Exceptions\RecordNotExistsException;
use App\Modules\Comments\Models\CommentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Responsible for interacting with the database.
 */
class CommentsRepository
{
    /**
     * Create new record in storage.
     *
     * @param int $parentId Comment parent id. If root comment $parentId = 0.
     * @param string $body Comment text
     * @param int $authorId Comment author id.
     * @return CommentModel New record.
     * @throws RecordNotExistsException
     */
    public function create(int $parentId, string $body, int $authorId): Model
    {
        $model = new CommentModel();
        $model->user_id = $authorId;
        $model->body = $body;
        $model->is_deleted = false;
        $model->parent_id = $parentId;

        if ($parentId === CommentModel::ROOT_PARENT_ID) {
            $model->depth = CommentModel::ROOT_DEPTH;
        } else {
            $parent = $this->getOne($parentId);
            $model->depth = $parent->depth + 1;
        }

        $model->created_at = date('Y-m-d H:i:s');

        $model->save();

        return $model;
    }

    /**
     * Update comment body text.
     *
     * @param int $id
     * @param string $body
     * @return CommentModel
     * @throws RecordNotExistsException
     */
    public function update(int $id, string $body): Model
    {
        $comment = $this->getOne($id);
        $comment->body = $body;

        $comment->save();

        return $comment;
    }

    /**
     * Get comment by id.
     *
     * @param int $id
     * @return CommentModel
     * @throws RecordNotExistsException
     */
    public function getOne(int $id): Model
    {
        $comment = CommentModel::query()->find($id);

        if (!$comment) {
            throw RecordNotExistsException::byId($id);
        }

        return CommentModel::query()->find($id);
    }

    /**
     * Get all records as collection.
     *
     * @return Collection
     */
    public function getAllWithAuthor(): Collection
    {
        return CommentModel::with('author')->get();
    }

    /**
     * Mark record as deleted bi id.
     *
     * @param int $id
     */
    public function markAsDeleted(int $id): void
    {
        CommentModel::query()->where(['id' => $id])
            ->update(['is_deleted' => true]);
    }
}
