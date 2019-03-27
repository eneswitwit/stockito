<?php

// namespace
namespace App\Http\Middleware;

// use
use Carbon\Carbon;
use Closure;
use Illuminate\View\View;

/**
 * Class PrivateResponse
 * @package App\Http\Middleware
 */
class PrivateResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        \Log::info('handling!');
        \Session::flush();
        \Cache::clear();
        \View::flushFinderCache();
        \View::flushSections();
        \View::flushState();
        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('clear-compiled');

        $response = $next($request);
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', Carbon::now()->format('D, d M Y H:i:s T'));
        $response->header('Cache-Control', 'no-store, no-cache, max-age=0, must-revalidate, private');

        return $response;
    }
}