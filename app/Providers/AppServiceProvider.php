<?php

namespace App\Providers;

use App\User;
//use Auth0\Login\Repository\Auth0UserRepository as Auth0UserRepository;
use Auth0\Login\Contract\Auth0UserRepository as Auth0UserRepository;
use Auth;
use Bouncer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Reminder;
use App\Observers\RecurringRemindersObserver;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind(
			\Auth0\Login\Contract\Auth0UserRepository::class,
			\App\Repositories\CustomUserRepository::class
		);
		$this->app->bind(\Spatie\Activitylog\ActivityLogger::class, \App\CustomActivityLogger::class);

	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {

		/**
		 * Pass Auth0 user information to every view (required for Bouncer)
		 */

		view()->composer('*', function ($view) {
			$user = User::current();
            if ($user){
                $user->recordActive();
            }

			$view->with('user', $user);
		});


		/**
		 * Paginate a standard Laravel Collection.
		 *
		 * @param int $perPage
		 * @param int $total
		 * @param int $page
		 * @param string $pageName
		 * @return array
		 */
		Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
			$page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
			return new LengthAwarePaginator(
				$this->forPage($page, $perPage),
				$total ?: $this->count(),
				$perPage,
				$page,
				[
					'path' => LengthAwarePaginator::resolveCurrentPath(),
					'pageName' => $pageName,
				]
			);
		});

        // Observer for reminder creation
        Reminder::observe(RecurringRemindersObserver::class);
	}
}
