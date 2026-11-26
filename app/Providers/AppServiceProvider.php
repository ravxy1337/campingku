<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    // Direktif Blade format rupiah
    public function boot(): void
    {
        Blade::directive('rupiah', fn (string $nilai) => "<?php echo 'Rp '.number_format((float) ($nilai), 0, ',', '.'); ?>");
    }
}
