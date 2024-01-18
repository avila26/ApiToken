<?php

namespace App\Http\Controllers;

use App\Models\TipoUsuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;//Libreria importada
use Illuminate\Support\Facades\Hash;//Libreria importada
use Illuminate\Support\Facades\Validator; //Libreria importada
use Illuminate\Support\Facades\DB;

class TiposUsuario extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = TipoUsuarios::where('estado', false)->get();
        return response()->json(['tipos'=> $tipos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    //guardar
    public function store(Request $request)
    {
         $tipo = new TipoUsuarios();
         $tipo->tipo= $request -> tipo;
         $tipo->save();
        return response()->json(['message' => 'Tipo de usuario creado correctamente'],);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $tipo)//buscar por tipo
    {
        $tipos = TipoUsuarios::where('tipo', $tipo)->get();
        return response()->json(['tipos'=> $tipos]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tipos = TipoUsuarios::find($id);
        if( $tipos)
        {
            $tipos->estado =true;
            $tipos->save();
        
        return response()->json(['message' => 'Tipo de usuario eliminado'],);
        }
    }
    
    public function calcularEdad()
    {
        return response()->json(['message' => 'Hola programadores'],);
    }

    public function ListadoTipoUsuario()
    {
        //Consulta de Query Biulder
         $tipoUsuario= DB::table('tipo_usuarios') //tipos_usuario es el nombre de la tabla 
         ->join('users', 'tipo_usuarios.id', '=','users.tipo_id') 
          ->select('users.id', 'users.name','users.email', 'tipo_usuarios.id as tipo_ids','tipo_usuarios.tipo')
          ->get();     
        return response()->json(['tipoUsuario' => $tipoUsuario], 200);
    }
}
