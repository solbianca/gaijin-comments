var Comments = (function () {

    const baseUrl = '/comment';

    deleteButtonListener = function (event) {
        btn = $(this);
        btn.prop('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')

        let commentId = $(this).data('id');
        deleteComment(commentId, btn);
    }

    let deleteComment = function (id, btn) {
        $.ajax({
            url: baseUrl + '/' + id,
            type: 'DELETE',
            success: function (result) {
                $('#comment-id-' + id).replaceWith($(result).find('#comment-id-' + id));
                $('#branch-' + id).prop('hidden', false);
            },
            error: function (xhr, error, thrown) {
                btn.prop('disabled', false);
                btn.html('Удалить');

                modal = $('#info-modal');
                modal.find('.modal-body').text(thrown);
                modal.modal('show');
            }
        });
    }

    let createComment = function (data) {
        $.ajax({
            url: baseUrl,
            type: 'POST',
            data: data,
            success: function (result) {
                let parentId = data['parent-id'];
                let parentBranch = $('#branch-' + parentId)
                parentBranch.append(result);

                let commentId = $(result).attr('id');
                parentBranch.find('#' + commentId).prop('hidden', false);

                $('#new-comment-modal').modal('hide');

                let createButton = $('#create-comment-btn');
                createButton.prop('disabled', false);
                createButton.text('Сохранить');

                parentBranch.find('#' + commentId).on('click', '.delete-comment-btn', deleteButtonListener);
            },
            error: function (xhr, error, thrown) {
                modal = $('#info-modal');
                modal.find('.modal-body').text(thrown);
                modal.modal('show');

                let createButton = $('#create-comment-btn');
                createButton.prop('disabled', false);
                createButton.text('Сохранить');
                $('#new-comment-modal').modal('hide');
            },
        });
    }

    let editComment = function (data) {
        var id = data['comment-id'];
        $.ajax({
            url: baseUrl + '/' + id,
            type: 'PATCH',
            data: data,
            success: function (result) {
                $('#comment-id-' + id).replaceWith($(result).find('#comment-id-' + id));

                let commentBranchId = $(result).attr('id');

                let commentBranch = $('#' + commentBranchId)
                commentBranch.prop('hidden', false);

                $('#edit-comment-modal').modal('hide');

                let editButton = $('#edit-comment-btn');
                editButton.prop('disabled', false);
                editButton.text('Сохранить');

                let commentId = commentBranch.data('id');
                commentBranch.find('#comment-id-' + commentId).on('click', '.delete-comment-btn', deleteButtonListener)
            },
            error: function (xhr, error, thrown) {
                modal = $('#info-modal');
                modal.find('.modal-body').text(thrown);
                modal.modal('show');

                let editButton = $('#edit-comment-btn');
                editButton.prop('disabled', false);
                editButton.text('Сохранить');
                $('#new-comment-modal').modal('hide');
            },

        });
    }

    return {
        deleteEventListener: function () {
            $('.delete-comment-btn').on('click', deleteButtonListener);
        },
        addListeners: function () {
            $('#create-comment-btn').on('click', function (event) {
                $(this).prop('disabled', true);
                $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')

                var $inputs = $('#create-comment-form :input');
                var values = {};
                $inputs.each(function () {
                    values[this.name] = $(this).val();
                });

                createComment(values);
            });

            $('#edit-comment-btn').on('click', function (event) {
                $(this).prop('disabled', true);
                $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')

                var $inputs = $('#edit-comment-form :input');
                var values = {};
                $inputs.each(function () {
                    values[this.name] = $(this).val();
                });

                editComment(values);
            });

            $('.show-hidden-comments').on('click', function (event) {
                parentBranch = $(this).closest('.branch');
                branches = parentBranch.find('.branch');
                branches.each(function () {
                    $(this).prop('hidden', false);
                });
                $(this).remove();
            });

            $('#new-comment-modal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let parentId = button.data('id');
                let modal = $(this)

                if (parentId !== 0) {
                    modal.find('.modal-title').text('Ответ на сообщение id:' + parentId);
                } else {
                    modal.find('.modal-title').text('Добавить новый комментарий');
                }
                modal.find('.modal-body #modal-parent-id-input').val(parentId);
            });

            $('#edit-comment-modal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let commentId = button.data('id');
                let modal = $(this)

                modal.find('.modal-title').text('Редактирование сообщения id:' + commentId);
                modal.find('.modal-body #modal-comment-id-input').val(commentId);
            });
        },
    }
})();

var Waifu = (function () {
    getWaifu = function () {
        let url = 'https://api.waifu.pics/sfw/waifu';

        url = fetch(url)
            .then(response => response.json())
            .then(setWaifuOnPage);
    }

    function setWaifuOnPage(waifu) {
        element = document.getElementById('waifu');
        element.src = waifu.url;

        lineBar.set(0);
        lineBar.animate(1, {
            duration: 10000
        }, function () {
            getWaifu();
        });
    }

    var lineBar = new ProgressBar.Line('#line-container', {
        color: 'orange',
        strokeWidth: 2,
        trailWidth: 0.5
    });

    lineBar.animate(1, {
        duration: 10000
    }, getWaifu());

    return {
        addListeners: function () {
            getWaifu();
        }
    };
})();

window.addEventListener('load', function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    Comments.deleteEventListener();
    Comments.addListeners();
    Waifu.addListeners();
});
