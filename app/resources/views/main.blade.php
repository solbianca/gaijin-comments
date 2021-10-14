@extends('layouts.app')

@section('content')
    <div class="container bg-light waifu-container crop">
        <img src="" id="waifu" class="img-fluid img-thumbnail mx-auto d-block waifu-img">
    </div>

    <div id="line-container"></div>

    <hr>

    @if (!\Illuminate\Support\Facades\Auth::guest())
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-comment-modal"
                            data-id=0>
                        Оставить комментарий
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="container py-5 comments-container branch" id="branch-0">
        @if (count($nodes) > 0)
            @foreach ($nodes as $node)
                @include('comment', ['node' => $node])
            @endforeach
        @else
            @include('comments-none')
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="new-comment-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ответ на сообщение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-comment-form">
                        <div class="form-group">
                            <label for="comment-body" class="col-form-label">Текст сообщения:</label>
                            <textarea class="form-control" name="comment-body" id="comment-body"></textarea>
                        </div>

                        <input type="hidden" class="form-control" name="parent-id" id="modal-parent-id-input">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="button" id="create-comment-btn" class="btn btn-primary">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-comment-modal" tabindex="-1" role="dialog"
         aria-labelledby="edit-comment-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактирование сообщения</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-comment-form">
                        <div class="form-group">
                            <label for="comment-body" class="col-form-label">Текст сообщения:</label>
                            <textarea class="form-control" name="comment-body" id="comment-body"></textarea>
                        </div>

                        <input type="hidden" class="form-control" name="comment-id" id="modal-comment-id-input">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="button" id="edit-comment-btn" class="btn btn-primary">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="info-modal" tabindex="-1" role="dialog" aria-labelledby="info-modal-label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

@endsection
