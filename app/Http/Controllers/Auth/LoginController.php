<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
        ];

        $authenticated = Auth::attempt($credentials, $request->validated('remember'));

        if(!$authenticated){
            throw ValidationException::withMessages(['email' => "The provided credentials don't match our record."]);
        }

        /**
         * @var \App\Models\User
         */
        $this->user = Auth::user();

        $request->session()->regenerate();

        if($this->user->hasPermissionTo('view-admin-dashboard')){
            return redirect()->route('admin.dashboard.index');
        }

        if ($this->user->hasPermissionTo('view-agent-dashboard')){
            return redirect()->route('agent.dashboard.index');
        }

        // Logout if no permission matches
        auth()->logout();

        abort(404, "No dashboard available for your account!. Please contact system administrator.");
    }
}
