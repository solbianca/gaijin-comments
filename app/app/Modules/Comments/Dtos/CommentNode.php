<?php

namespace App\Modules\Comments\Dtos;

use App\Modules\Comments\Models\CommentModel;

class CommentNode
{
    /**
     * Comment associated with current node.
     */
    private CommentModel $comment;

    /**
     * Indexed array of child nodes in correct order.
     *
     * @var CommentNode[]
     */
    private array $children = [];

    public function __construct(CommentModel $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Add children nodes to current node.
     *
     * @param array $children
     */
    public function addChildren(array $children): void
    {
        foreach ($children as $child) {
            $this->children[] = $child;
        }
    }

    /**
     * Get node comment.
     *
     * @return CommentModel
     */
    public function getComment(): CommentModel
    {
        return $this->comment;
    }

    /**
     * Check that node has children.
     *
     * @return bool
     */
    public function hasChildren(): bool
    {
        return $this->children != [];
    }

    /**
     * Get children nodes.
     *
     * @return CommentNode[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}
