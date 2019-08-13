<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 4/3/19
 * Time: 2:07 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

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
        Log::info(json_encode($request->headers->all()));
        Log::info(json_encode($request->path()));
        Log::info(json_encode($request->method()));
        Log::info(json_encode($request->all()));
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Socket-ID, X-HTTP-Method-Override,X-Requested-With');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
//        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN');
        $response->headers->set('Access-Control-Expose-Headers', 'Authorization, authenticated');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
//        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        return $response;
    }

}
