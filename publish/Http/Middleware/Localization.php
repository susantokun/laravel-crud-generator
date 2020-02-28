<?php

namespace App\Http\Middleware;

use Closure;

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Instagram       : @susantokun
 * | Website         : http://www.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Friday, 28th February 2020 2:50:20 pm
 * | Last Modified   : Friday, 28th February 2020 2:50:20 pm
 * |==============================================================|
 */

class Localization
{
    public function handle($request, Closure $next)
    {
        if (\Session::has('locale')) {
            \App::setlocale(\Session::get('locale'));
        }
        return $next($request);
    }
}
