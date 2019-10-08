<?php namespace App\Http\Middleware;

// First copy this file into your middleware directoy

use Closure;

class CheckAcl
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
        // Get the required roles from the route
        $acls = $this->getRequiredAclForRoute($request->route());

        // Check if a role is required for the route, and
        // if so, ensure that the user has that role.
        if (!$acls) {
            return $next($request);
        } else {
            $passed = false;
            foreach ($acls as $r) {
                if (\Gate::allows($r)) {
                    $passed = true;
                    break;
                }
            }
            if ($passed) {
                return $next($request);
            }
        }
        return response([
            'error' => [
                'code' => 'INSUFFICIENT_ROLE',
                'description' => 'You are not authorized to access this resource.',
            ],
        ], 401);

    }

    private function getRequiredAclForRoute($route)
    {
        $actions = $route->getAction();
        return isset($actions['acl']) ? $actions['acl'] : null;
    }

}
