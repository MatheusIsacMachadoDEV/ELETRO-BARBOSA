<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
        $credentials = $request->only('email', 'password');

        // if($credentials == 'teste'){
        //     return redirect()->route('home'); // Redirecionar para a página após o login
        // } else {            
        //     return back()->withErrors(['message' => 'Credenciais inválidas.']);
        // }

        if (Auth::attempt($credentials)) {
            // Autenticação bem-sucedida
            return redirect()->route('home'); // Redirecionar para a página após o login
        } else {
            // Autenticação falhou
            return back()->withErrors(['message' => 'Credenciais inválidas.']);
        }
    }
}
