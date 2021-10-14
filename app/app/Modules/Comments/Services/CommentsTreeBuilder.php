<?php

namespace App\Modules\Comments\Services;

use App\Modules\Comments\Dtos\CommentNode;
use App\Modules\Comments\Models\CommentModel;

/**
 * Comments tree builder. Encapsulate all logic to build valid comments tree from flat array.
 */
class CommentsTreeBuilder
{
    /**
     * Build comments tree from flat array.
     *
     * @param CommentModel[] $comments
     * @return CommentNode[] Comments as tree structure.
     */
    public function build(array $comments): array
    {
        return $this->buildTree($comments, CommentModel::ROOT_PARENT_ID);
    }

    private function buildTree(array &$comments, int $parentId): array
    {
        $tree = [];

        foreach ($comments as $comment) {
            if ($comment->parent_id !== $parentId) {
                continue;
            }

            $node = new CommentNode($comment);
            $children = $this->buildTree($comments, $comment->id);
            if ($children) {
                $node->addChildren($children);
            }

            $tree[$comment->id] = $node;

            unset($comments[$comment->id]);
        }

        return $tree;
    }
}
