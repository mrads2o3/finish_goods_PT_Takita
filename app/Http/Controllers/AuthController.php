<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register() {
        if(User::count()){
            return redirect()->route('login')->with('error', 'Registration closed!');
        }
        return view('register');
    }
    public function registerPost(Request $req) {
        $user = new User();

        $user->name = $req->name;
        $user->username = $req->username;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);

        $user->save();

        return redirect()->route('login')->with('success', 'Registration success!');
    }
    
    public function login() {
        return view('login', ['register'=>User::count()]);
    }
    public function loginPost(Request $req) {
        $cred = [
            'username' => $req->username,
            'password' => $req->password
        ];

        if(Auth::attempt($cred)){
            return redirect('/dashboard')->with('success', 'Login successfully!');
        }

        return back()->with('error', 'Wrong Username or Password!');
    }

    public function logout(){
        Auth::logout();

        return redirect()->route('login')->with('success', 'Logout success!');
    }
}
