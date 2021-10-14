<?php

namespace App\Modules\Comments\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Model to interact with storage.
 *
 * @property int $id
 * @property int $user_id
 * @property string $body
 * @property int $is_deleted
 * @property int $parent_id
 * @property int $depth
 * @property int $order
 * @property string $path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CommentModel extends Model
{
    public const ROOT_PARENT_ID = 0;
    public const ROOT_DEPTH = 0;

    public const SECONDS_TO_CHANGE = 3600; // one hour

    protected $table = 'comments';

    /**
     * Get the author that of the comment.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
