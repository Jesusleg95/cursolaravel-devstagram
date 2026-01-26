<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Validation\ValidatesRequests;

class RegisterController extends Controller{

    use ValidatesRequests;

    public function index() {
        return view('auth.register');
    }

    public function store(Request $request) {
        //dd($request);

        //Modificar request
        $request->request->add(['username' => Str::slug($request->username)]);

        //Validacion
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        //Autenticar un usuario
        Auth::attempt([
            'email' => $request->email, 
            'password' => $request->password
        ]);

        //Redireccionar
        // return redirect()->route('posts.index');
        return redirect()->route('posts.index', Auth::user());
    }
}
