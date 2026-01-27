<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PerfilController extends Controller
{
    use ValidatesRequests;
    
    public function index(){
        return view('perfil.index');
    }
    
    public function store(Request $request){
        
        $request->request->add(['username' => Str::slug($request->username)]);
        $this->validate($request, [
            'username' => ['required','unique:users,username,'.Auth::user()->id,'min:3','max:20', 'not_in:twitter,editar-perfil'],
        ]);

        if($request->imagen){
            $manager = new ImageManager(new Driver());
            $imagen = $request->file('imagen');
            //Generar id unico para las imagenes
            $nombreImagen = Str::uuid().".".$imagen->extension();
            //Subir la imagen al servidor
            $imagenServidor = $manager->read($imagen);
            //Agregar efecto
            $imagenServidor->scale(1000, 1000);
            $imagenPath = public_path('perfiles').'/'.$nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        //Guardar cambios
        $usuario = User::find(Auth::user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? Auth::user()->imagen ?? null;
        $usuario->save();

        //Redireccionar
        return redirect()->route('posts.index', $usuario->username);
    }

}
