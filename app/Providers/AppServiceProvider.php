<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        Blade::directive('active', function ($pattern) {
            return "<?php echo request()->routeIs({$pattern}) ? 'active' : ''; ?>";
        });

        Relation::morphMap([
            'sale' => 'App\Models\Sale',
            'purchase_order' => 'App\Models\PurchaseOrder',
            'work_order' => 'App\Models\WorkOrder',
        ]);
    }
}
