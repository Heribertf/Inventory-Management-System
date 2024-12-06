<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Fetch the user's departments
        $user = $request->user();
        $departments = $user->departments ?? [];

        // dd($departments);
        $request->session()->put('departments', $departments);
        // $departments = $user->departments()->pluck('department')->toArray();
        // $departmentsString = implode(',', $departments);

        // $request->session()->put('loggedin', true);
        $request->session()->put('id', $user->user_id);
        // $request->session()->put('email', $user->email);
        // $request->session()->put('fullname', $user->fullname);
        // $request->session()->put('user_role', $user->role);
        // $request->session()->put('user_type', $user->type);
        // $request->session()->put('departments', $departmentsString);


        if ($request->user()->type == 1) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->type == 2) {
            if (in_array('FR', $departments)) {
                return redirect()->route('fr.home');
            } elseif (in_array('DC', $departments)) {
                return redirect()->route('cd.home');
            } elseif (in_array('INS', $departments)) {
                return redirect()->route('ins.home');
            } else {
                return redirect()->route('department.home');
            }
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
