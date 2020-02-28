> laravel new blog
> cd blog && code .
> composer require laravel/ui
> php artisan ui vue --auth
> npm install

setting .env

> composer require susantokun/laravel-crud-generator
> php artisan vendor:publish --provider="Susantokun\LaravelCrudGenerator\LaravelCrudGeneratorServiceProvider"

copy content file or remove _lcg in this file :

1. database/seeds/DatabaseSeeder_lcg.php (*copy content and delete*)
2. List itemresources/js/app_lcg.js
3. List itemresources/js/bootstrap_lcg.js
4. List itemresources/sass/app_lcg.scss
5. List itemresources/views/auth_lcg
6. List itemresources/views/layouts/app_lcg.blade.php
7. List itemresources/views/home_lcg.blade.php
8. List itemroutes/web_lcg.php

> php artisan storage:link
> composer dump-autoload
> php artisan migrate --seed

> npm install sweetalert2
> npm install @fortawesome/fontawesome-free
> npm install jquery.easing
> npm install chart.js
> npm install datatables.net-bs4

or 

> npm install sweetalert2 @fortawesome/fontawesome-free jquery.easing chart.js datatables.net-bs4

> composer require realrashid/sweet-alert

**app/Http/Kernel.php**
protected $middlewareGroups = [
    'web' => [
        .....
        \RealRashid\SweetAlert\ToSweetAlert::class,
        \App\Http\Middleware\Localization::class,
    ],
];

**vendor/realrashid/sweet-alert/src/ToSweetAlert.php** (*optional*)
if ($request->session()->has('toast_success')) {
    alert()->toast($request->session()->get('toast_success'), 'success')->middleware()
        ->animation('fadeInRight', 'fadeOutRight')
        ->timerProgressBar()
        ->position('bottom-end');
}

if ($request->session()->has('toast_error')) {
    toast($request->session()->get('toast_error'), 'error')->middleware()
        ->animation('fadeInRight', 'fadeOutRight')
        ->timerProgressBar();
}

> npm run dev
> php artisan serve

email : admin@mail.com
password : password

-- API --
php artisan crud:api CategoryCertificate --namespace-controller="Info\Api" --namespace-model="Models\Info" --namespace-resource="Resources\Info" --route-group="info" --fields_from_file="json/info_category_certificates.json"

php artisan crud:api-controller CategoryCertificateController --namespace-controller="Http\Controllers\Info\Api" --namespace-model="Models\Info" --namespace-resource="Resources\Info" --name-model="CategoryCertificate" --force

php artisan crud:api-resource CategoryCertificateResource --namespace-resource="Resources\Info" --force


-- CRUD --
php artisan crud:generate CategoryCertificate --namespace-controller="Info" --namespace-model="Models\\Info" --view-path="info" --route-group="info" --form-helper="html" --fields_from_file="json/info_category_certificates.json"

php artisan crud:controller CategoryCertificateController --name-model="CategoryCertificate" --namespace-controller="Http\Controllers\Info" --namespace-model="Models\Info" --view-path="info" --route-group=info --force

php artisan crud:model CategoryCertificate --fillable="['title', 'body']" --namespace-model="Models\Info" --force

php artisan crud:migration category_certificates --schema="title#string; body#text"

