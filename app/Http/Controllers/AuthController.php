<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;//Libreria importada
use Illuminate\Support\Facades\Hash;//Libreria importada
use Illuminate\Support\Facades\Validator; //Libreria importada



class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = User::where('estado', false)->get();
        return response()->json(['usuarios' => $usuario], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validateUsuario = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'tipo_id'=> 'required'
            ]
        );

        if ($validateUsuario->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Existen campos vacios',
                'errors' => $validateUsuario->errors()
            ], 401);
        }
        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'tipo_id' => $request->tipo_id,
        ]);


        return response()->json([
            'usuario' => $usuario,
            'message' => 'Usuario creado correctamente',
            'token' => $usuario->createToken("API TOKEN")->plainTextToken
        ], 201);
    }

    /**
     * Display the specified resource.
     */

     public function logear(Request $request){
        $validateUsuario = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ]
        );
        if ($validateUsuario->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validaciones requeridas',
                'errors' => $validateUsuario->errors()
            ], 401);
        }
        //Valida si el email y el password estan en la base de datos
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas verifique email o contraseña',
            ], 401);
        }       
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'message' => 'Usuario logeado correctamente',
            'token' => $user->createToken("API TOKEN")->plainTextToken
      ],200);
    }

    public function showUser(string $name)
    {
        $usuario= User::where('name',$name)->get();
        return response()->json(['usuario' => $usuario]);
        
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
        $validateUsuario = Validator::make(
            $request->all(),
            [
                //AQUI ESTAN LOS CAMPOS A ACTUALIZAR
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]
        );
        //VALIDAR SI VIENE VACIO
        if ($validateUsuario->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Existen campos vacios',
                'errors' => $validateUsuario->errors()
            ], 401);
        }
        //CONSULTAR SI LOS DATOS EXISTEN EN LA BD POR MEDIO DEL ID PARA PODER ACTUALIZAR
        $usuario = User::find($id);
        //Validad si el usuario segun el id Existe
            if(!$usuario)    
            {
                return response()->json([
                    'message' => 'Usuario  no encontrado',], 404);
            }
            $usuario->name = $request->name;
            $usuario-> email = $request->email;
            $usuario-> password = $request->password;
            $usuario->save();

        return response()->json([
            'message' => 'Usuario  actualizado',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Validar si el ID viene vacio
        if(!$id)
        {
            return response()->json([
            'message' => 'El codigo del usuario esta vacio', ],
            401);
        }
        //Valida si existe
        $usuario = User::find($id);
        if($usuario){
            $usuario->estado=true;
            $usuario->save();
            return response()->json([
                'message' => 'Usuario Eliminado', ],
                200);
        }
        //Si el usuario no existe
        else{ 
            return response()->json([
                'message' => 'El usuario no existe', ],
                404);//Cuando no existe 404
        }
    }
}
