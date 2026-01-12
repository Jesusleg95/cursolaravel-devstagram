<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImagenController extends Controller{
    public function store(Request $request){
        $manager = new ImageManager(new Driver());

        $imagen = $request->file('file');
        //Generar id unico para las imagenes
        $nombreImagen = Str::uuid().".".$imagen->extension();
        //Subir la imagen al servidor
        $imagenServidor = $manager->read($imagen);
        //Agregar efecto
        $imagenServidor->scale(1000, 1000);

        
        $imagenPath = public_path('uploads').'/'.$nombreImagen;
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' => $nombreImagen]);
    }
}
