<?php

namespace Susantokun\LaravelCrudGenerator;

use Illuminate\Support\ServiceProvider;

class LaravelCrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            'Susantokun\LaravelCrudGenerator\Commands\CrudApiCommand',
            'Susantokun\LaravelCrudGenerator\Commands\CrudApiControllerCommand',
            'Susantokun\LaravelCrudGenerator\Commands\CrudApiResourceCommand',
            'Susantokun\LaravelCrudGenerator\Commands\CrudCommand',
            'Susantokun\LaravelCrudGenerator\Commands\CrudControllerCommand',
            'Susantokun\LaravelCrudGenerator\Commands\CrudModelCommand',
            'Susantokun\LaravelCrudGenerator\Commands\CrudMigrationCommand',
            'Susantokun\LaravelCrudGenerator\Commands\CrudViewCommand',
            'Susantokun\LaravelCrudGenerator\Commands\CrudLangCommand'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravelcrudgenerator.php' => config_path('laravelcrudgenerator.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/views/' => base_path('resources/views/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/lang/' => base_path('resources/lang/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/views/auth/' => base_path('resources/views/auth/'),
        ]);

        $this->publishes([
            __DIR__ . '/stubs/' => base_path('resources/laravel-crud-generator/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/Http/Middleware/' => base_path('app/Http/Middleware/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/js/' => base_path('resources/js/'),
        ]);
    }
}
