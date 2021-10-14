@php
    /**
     * @var \App\Modules\Comments\Dtos\CommentNode $node
     */
    $maxDepth = 9   ;

    if ($node->getComment()->depth > $maxDepth) {
        $padLeftLevel = $maxDepth;
    } else {
        $padLeftLevel = $node->getComment()->depth;
    }

    $isHidden = false;
    if ($node->getComment()->depth >=3) {
        $isHidden = true;
    }
@endphp

<div class="branch" id="branch-{{ $node->getComment()->id }}" data-id="{{ $node->getComment()->id }}"
     @if($isHidden) hidden @endif>
    <div class="comment border-bottom border-gray level-{{ $padLeftLevel }}"
         id="comment-id-{{ $node->getComment()->id }}">
        <div class="comment-dot"></div>
        <div>
            <b>id:{{ $node->getComment()->id }}</b> {{ $node->getComment()->created_at }} {{ $node->getComment()->author->name }}

            @if (!\Illuminate\Support\Facades\Auth::guest())
                <button type="button" class="badge badge-primary" data-toggle="modal" data-target="#new-comment-modal"
                        data-id="{{ $node->getComment()->id }}">
                    Ответить
                </button>
            @endif

            @if ($node->getComment()->created_at->timestamp + App\Modules\Comments\Models\CommentModel::SECONDS_TO_CHANGE >= time())
                @if (!$node->getComment()->is_deleted && \Illuminate\Support\Facades\Auth::id() === $node->getComment()->user_id)
                    <button type="button" class="badge badge-secondary" data-toggle="modal"
                            data-target="#edit-comment-modal"
                            data-id="{{ $node->getComment()->id }}">
                        Редактировать
                    </button>

                    <button type="button" class="badge badge-danger delete-comment-btn"
                            data-target="#delete-comment-modal"
                            data-id="{{ $node->getComment()->id }}">
                        Удалить
                    </button>
                @endif
            @endif

        </div>
        @if ($node->getComment()->is_deleted)
            @include('comment-deleted')
        @else
            @include('comment-body')
        @endif
    </div>

    @if ($node->hasChildren())
        @if ($node->getComment()->depth === 2)
            <div class="more-comments">
                <button type="button" class="btn btn-primary btn-sm show-hidden-comments">
                    Показать скрытые комментарии
                </button>
            </div>
        @endif

        @foreach($node->getChildren() as $node)
            @include('comment', ['node' => $node])
        @endforeach

    @endif

</div>
