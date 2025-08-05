<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::guard('employees')->check()) {
            return redirect()->route('management.dashboard');
        }

        return view('management.layouts.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('employees')->attempt($credentials)) {
            $employee = Employee::where('email', $credentials['email'])->first();
            if ($employee->del_flag == 1) {
                Auth::guard('employees')->logout();
                return back()->withErrors([
                    'email' => 'Your account is deactivated.'
                ]);
            }

            $request->session()->regenerate();
            $request->session()->put([
                'employee_login' => true,
                'id' => $employee->id,
                'position' => $employee->position,
            ]);
            return redirect()->intended('/management');
        }

        return back()->withErrors([
            'email' => 'Invalid email/password'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('employees')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('management.showLogin');
    }
}
