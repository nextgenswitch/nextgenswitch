<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('config.licence', function (): array {
            return $this->readJsonFileAsArray(storage_path('licence.json'));
        });

        $this->app->singleton('config.settings.switch', function (): array {
            return $this->decodeJsonString(Setting::get_settings_json('switch'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        $this->registerSqliteFunctions();

        if ($this->app->runningInConsole()) {
            return;
        }

        if ($this->is_ssl()) {
            URL::forceScheme('https');
        }

        try {
            Config::set('licence', $this->app->make('config.licence'));
            Config::set('settings.switch', $this->app->make('config.settings.switch'));
        } catch (\Throwable $exception) {
            Log::warning('Unable to initialise runtime configuration', [
                'exception' => $exception,
            ]);
        }
    }

    function is_ssl()
    {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS']))
                return true;
            if ('1' == $_SERVER['HTTPS'])
                return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }

    private function readJsonFileAsArray(string $path): array
    {
        if (!is_file($path) || !is_readable($path)) {
            return [];
        }

        return $this->decodeJsonString((string) file_get_contents($path));
    }

    private function decodeJsonString(?string $json): array
    {
        if (!is_string($json) || $json === '') {
            return [];
        }

        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Failed parsing JSON configuration payload', [
                'error' => json_last_error_msg(),
            ]);

            return [];
        }

        return is_array($decoded) ? $decoded : [];
    }

    private function registerSqliteFunctions(): void
    {
        $connection = DB::connection();

        if ($connection->getDriverName() !== 'sqlite') {
            return;
        }

        $connection->getPdo()->sqliteCreateFunction('FIND_IN_SET', static function ($val, $set) {
            if ($val === null || $set === null) {
                return 0;
            }

            $items = array_map('trim', explode(',', $set));
            $position = array_search($val, $items, true);

            return $position === false ? 0 : $position + 1;
        }, 2);
    }
}
