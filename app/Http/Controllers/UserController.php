<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function loginPaciente(Request $request)
    {
        $this->validate($request, [
            'nombreUsuario' => 'required|string',
            'clave' => 'required|string',
            'idDispositivo' => 'required|integer'
        ]);

        $user = UserModel::loginUserPaciente(
            $request->input('nombreUsuario'),
            $request->input('clave'),
            $request->input('idDispositivo')
        );

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas o dispositivo no asociado'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => $user[0] // Devuelve el primer resultado (debería ser único)
        ]);
    }

    public function loginDoctor(Request $request)
    {
        $this->validate($request, [
            'nombreUsuario' => 'required|string',
            'clave' => 'required|string'
        ]);

        $user = UserModel::loginUserDoctor(
            $request->input('nombreUsuario'),
            $request->input('clave')
        );

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => $user[0]
        ]);
    }

    public function loginObservador(Request $request)
    {
        $this->validate($request, [
            'nombreUsuario' => 'required|string',
            'clave' => 'required|string'
        ]);

        $user = UserModel::loginUserObservador(
            $request->input('nombreUsuario'),
            $request->input('clave')
        );

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => $user[0]
        ]);
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = UserModel::loginUser($request->input('email'), $request->input('password'));

        if (empty($user)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Incluir el rol en la respuesta
        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user[0]->id,
                'name' => $user[0]->name,
                'email' => $user[0]->email,
                'role' => $user[0]->role
            ]
        ]);
    }

    public function getMachinery(Request $request)
    {
        $userId = $request->input('user_id'); // Este ID puede venir del token o de la solicitud
        $machinery = UserModel::getMachineryByUser($userId);

        return response()->json(['machinery' => $machinery]);
    }

    public function getUsers()
    {
        $machinery = UserModel::getUsers();

        return response()->json($machinery, 200);
    }
}
