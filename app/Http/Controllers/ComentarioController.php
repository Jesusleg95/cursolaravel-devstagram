<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    use ValidatesRequests;

    public function store(Request $request, User $user, Post $post){
        //Validar
        $this->validate($request, [
            'comentario' => 'required|max:255'
        ]);

        //Almacenar el resultado
        Comentario::create([
            'user_id' => Auth::user()->id,
            'post_id' => $post->id,
            'comentario' => $request->comentario
        ]);

        //Imprimir mensaje
        return back()->with('mensaje', 'Comentario Realizado Correctamente');
        
    }
}
