<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 4/3/19
 * Time: 2:07 PM
 */

namespace App\Http\Middleware;

use Closure;

class EnableCors
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Socket-ID, X-HTTP-Method-Override,X-Requested-With');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        return $response;
    }

}
