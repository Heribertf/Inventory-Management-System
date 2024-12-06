<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepartmentAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $departmentCode
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function handle(Request $request, Closure $next, $departmentCode): Response
    {
        $departmentsArray = session('departments', []);

        if (in_array($departmentCode, $departmentsArray)) {
            return $next($request);
        }

        $backUrl = route('department.home');
        return response()->view('departments.403', ['backUrl' => $backUrl], 403);
    }
}
