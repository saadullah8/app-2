<?php

namespace App\Http\Middleware;

use App\Models\StoreOpening;
use Closure;
use Illuminate\Support\Carbon;

class CheckOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $store = StoreOpening::first();
        $currentTime = date('H:i: a', strtotime(Carbon::now()));
        $startTime = date('H:i: a', $store->opening_time);
        $endTime = date('H:i: a', $store->closing_time);
        if ($store->status) {
            return $next($request);
//            if ($currentTime > $startTime) {
//                if ($currentTime < $endTime) {
//                    return $next($request);
//                }
//            }
        }

        return redirect('storeClosed');
    }
}
