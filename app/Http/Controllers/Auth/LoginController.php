<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
    	$this->validateLogin($request);

        // check if user account is not created yet or not verified by admin
        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status_id' => 1])) {
            return $this->sendFailedLoginResponse($request);
        } else {
        	// login the user
        	Auth::login($request->user());

            // if user is 'admin'
            if ($request->user()->user_level_id ===2) {
                return redirect('admin');
            }

        	// if user is 'editor'
        	if ($request->user()->user_level_id === 4) {
        		return redirect('editor');
        	}

        	return redirect()->intended($this->redirectTo);
        }
    }
}
