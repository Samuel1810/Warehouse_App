<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function index(){
        return view('auth.login');
    }

    public function store(Request $request){
        $request->validate([
            'email'=> 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required'=> 'O campo email é obrigatório!',
            'email.email'=> 'Esse campo tem que ter um email válido!',
            'password.required'=> 'O campo palavra-passe é obrigatório!',
            'password.min'=> 'Esse campo deve ter no mínimo 6 caractéres!'
        ]); 

        $credentials = $request->only('email','password');
        $authenticated = Auth::attempt($credentials);

        if(!$authenticated){
            return redirect()->route('login.index')->withErrors(['error'=> 'Email ou palavra-passe inválidos']);
        }
        
        if (auth()->user()->user_type === 1) {
            return redirect()->route('admin.material');
        } else {
            return redirect()->route('user.material');
        }
    }

    public function destroy(){
        Auth::logout();

        return redirect()->route('welcome');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

}
