<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends MasterController
{
    use AuthenticatesUsers;

    public $redirectTo = '/';

    public function __construct(User $user)
    {
        //$this->middleware('guest');
        $this->user = $user;
    }

    public function getRegister(){
        $this->user->validationRules['password'] = 'required|confirmed';
        return $this->render('auth.register');
    }

    public function postLogin(Request $request){
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(){
        return response()->json([$this->username() => [\Lang::get('auth.failed')]], 422);
    }



    public function getLogin(){
        return $this->render('auth.login');
    }

    protected function username(){
        return 'email';
    }
}