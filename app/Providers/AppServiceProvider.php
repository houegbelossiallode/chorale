<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\SousMenu;
use App\Observers\RoleObserver;
use App\Observers\SousmenuObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\AdminSidebarComposer;

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

        View::composer('*', function ($view) {
            $user = Auth::user();

            $menus = [];
            if ($user && $user->role) {
                $accessibleSousMenus = $user->role->permissions->where('is_granted', true)->pluck('sous_menu_id')->toArray();

                $menus = Menu::with([
                    'sousmenus' => function ($query) use ($accessibleSousMenus) {
                        $query->whereIn('id', $accessibleSousMenus)
                            ->where('actif', 'OUI')
                            ->where('is_show', 'OUI');
                    }
                ])->whereHas('sousmenus', function ($query) use ($accessibleSousMenus) {
                    $query->whereIn('id', $accessibleSousMenus)
                        ->where('actif', 'OUI')
                        ->where('is_show', 'OUI');
                })->get();
            }

            $view->with('mainmenus', $menus);
        });

        SousMenu::observe(SousmenuObserver::class);
        Role::observe(RoleObserver::class);
    }
}
