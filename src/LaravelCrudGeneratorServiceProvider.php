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
            __DIR__ . '/../publish/database/migrations/' => base_path('database/migrations/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/database/seeds/' => base_path('database/seeds/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/Http/Controllers/' => base_path('app/Http/Controllers/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/Http/Middleware/' => base_path('app/Http/Middleware/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/Models/' => base_path('app/Models/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/resources/' => base_path('resources/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/routes/' => base_path('routes/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/storage/app/public/uploads/' => base_path('storage/app/public/uploads/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/public/js/' => base_path('public/js/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/json/' => base_path('json/'),
        ]);

        // $this->publishes([
        //     __DIR__ . '/stubs/' => base_path('resources/laravel-crud-generator/'),
        // ]);
    }
}
