<?php
namespace App\Http\Middleware;
use Closure;
class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // var_export($request->header('origin'));
        if($request->header('origin')=='http://projectdevzone.com' || $request->header('origin')=='http://*.ooionline.com' || $request->header('origin')=='http://cartetppro.fr' || $request->header('origin')=='https://cartetppro.fr'|| $request->header('origin')=='http://localhost:3000'){
            return $next($request)

        // ->header('Access-Control-Allow-Origin', 'file://')
//            ->header('Access-Control-Allow-Origin', '*')
                // ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Origin', $request->header('origin'))

//            ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
                // ->header('Access-Control-Allow-Origin', 'http://192.168.1.32:5000')
            // ->header('Access-Control-Allow-Origin', 'http://localhost:18788')
        // ->header('Access-Control-Allow-Origin', 'http://localhost:9000')

        ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Allow-Headers', 'Content-Type,User-Id,API-Token,Origin,X-XSRF-TOKEN,ops_session,Accept')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE,OPTIONS');
            return $next($request);
        }else{
            return $next($request);
                // ->header('Access-Control-Allow-Origin', 'file://')
//            ->header('Access-Control-Allow-Origin', '*')
                // ->header('Access-Control-Allow-Origin', '*')
//                ->header('Access-Control-Allow-Origin', 'http://carte.projectdevzone.com')

//            ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
                // ->header('Access-Control-Allow-Origin', 'http://192.168.1.32:5000')
                // ->header('Access-Control-Allow-Origin', 'http://localhost:18788')
                // ->header('Access-Control-Allow-Origin', 'http://localhost:9000')

//                ->header('Access-Control-Allow-Credentials', 'true')
//                ->header('Access-Control-Allow-Headers', 'Content-Type,User-Id,API-Token,Origin,X-XSRF-TOKEN,ops_session,Accept')
//                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE,OPTIONS');
        }
    }
}