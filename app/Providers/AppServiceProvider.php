<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Set custom pagination view
        \Illuminate\Pagination\Paginator::defaultView('pagination.tailwind');
        
        // Add custom Blade directive for datetime formatting
        \Illuminate\Support\Facades\Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression) ? ($expression)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A'; ?>";
        });
        
        \Illuminate\Support\Facades\Blade::directive('date', function ($expression) {
            return "<?php echo ($expression) ? ($expression)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y') : 'N/A'; ?>";
        });
    }
}
