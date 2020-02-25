
app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        .....
        \App\Http\Middleware\Localization::class, // here
    ],
];

routes/web.php
Route::get('locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});

resources/js/app.js
Vue.component('language-switcher', require('./components/LanguageSwitcher.vue').default);

> composer require laravel/ui --dev
> php artisan ui vue --auth (No -> for layouts/app.blade.php)
> npm install && npm run dev

> npm install sweetalert2
resources/js/app.js
const Swal = (window.Swal = require("sweetalert2"));

> composer require realrashid/sweet-alert
> php artisan sweetalert:publish

app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        .....
        \RealRashid\SweetAlert\ToSweetAlert::class, // here
    ],
];

vendor/realrashid/sweet-alert/src/ToSweetAlert.php
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


any views
@section('title','Name of page')

for file image
json image type: not add #no
