<?php

namespace App\Modules\Comments\Providers;

use App\Modules\Comments\Repositories\CommentsRepository;
use App\Modules\Comments\Services\CommentsService;
use App\Modules\Comments\Services\CommentsTreeBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Register services in di container.
 */
class CommentsServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->singleton(CommentsRepository::class, function () {
            return new CommentsRepository();
        });

        $this->app->singleton(CommentsService::class, function (Application $app) {
            return new CommentsService($app->get(CommentsRepository::class), new CommentsTreeBuilder());
        });
    }
}
