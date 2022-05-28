<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {   

        if (Auth::guard($guard)->check()) {
            
            $user = Auth::user();
            if ($user->user_type == 'company-admin') {
               return redirect(route('companyAdminDashboard'));
            }

            if ($user->user_type == 'company-instructor') {
                return redirect(route('companyInstructorDashboard'));
            }

            if (session('cp_id_link') != NULL) 
            {   
                return redirect()->intended(route('course',[session('cp_slug'),session('affilite_id'),session('cp_id_link'),session('session_link')]));
            }

            if ($user->isAdmin()){
                return redirect(route('admin'));
            }
            return redirect(route('dashboard'));

            //return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}