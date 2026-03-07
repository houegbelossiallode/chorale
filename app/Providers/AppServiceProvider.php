<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\SousMenu;
use App\Models\Post;
use App\Models\Event;
use App\Observers\RoleObserver;
use App\Observers\SousmenuObserver;
use App\Observers\PostObserver;
use App\Observers\EventObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\AdminSidebarComposer;
use Illuminate\Support\Facades\Event as EventFacade;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Models\AuditLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }


        View::composer('*', function ($view) {
            $user = Auth::user();

            $menus = [];
            if ($user && $user->role) {
                $accessibleSousMenus = $user->role->permissions()
                    ->whereRaw('is_granted = true')
                    ->pluck('sous_menu_id')
                    ->toArray();

                $menus = Menu::with([
                    'sousMenus' => function ($query) use ($accessibleSousMenus) {
                        $query->whereIn('id', $accessibleSousMenus)
                            ->where('actif', 'OUI')
                            ->where('is_show', 'OUI');
                    }
                ])->whereHas('sousMenus', function ($query) use ($accessibleSousMenus) {
                    $query->whereIn('id', $accessibleSousMenus)
                        ->where('actif', 'OUI')
                        ->where('is_show', 'OUI');
                })->get();
            }

            $view->with('mainmenus', $menus);
        });

        SousMenu::observe(SousmenuObserver::class);
        Role::observe(RoleObserver::class);
        Post::observe(PostObserver::class);
        Event::observe(EventObserver::class);

        // Audit Logs
        EventFacade::listen(Login::class, function ($event) {
            AuditLog::create([
                'user_id' => $event->user->id,
                'event' => 'login',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        EventFacade::listen(Logout::class, function ($event) {
            if ($event->user) {
                AuditLog::create([
                    'user_id' => $event->user->id,
                    'event' => 'logout',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        });

        EventFacade::listen(Failed::class, function ($event) {
            AuditLog::create([
                'user_id' => $event->user ? $event->user->id : null,
                'event' => 'failed_login',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}
