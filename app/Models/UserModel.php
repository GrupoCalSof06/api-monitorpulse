<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    public static function loginUserPaciente(string $nombreUsuario, string $clave, int $idDispositivo)
    {
        return DB::select("SELECT idUsuario,nombre,nombreUsuario,clave,tipoUsuario,idDispositivo FROM usuarios 
        WHERE nombreUsuario = ? AND clave = ? AND idDispositivo = ? AND tipoUsuario = 'Paciente'", [$nombreUsuario, $clave, $idDispositivo ]);
    }
    public static function loginUserDoctor(string $nombreUsuario, string $clave)
    {
        return DB::select("SELECT idUsuario,nombre,nombreUsuario,clave,tipoUsuario,idDispositivo FROM usuarios 
        WHERE nombreUsuario = ? AND clave = ? AND tipoUsuario = 'Doctor'", [$nombreUsuario, $clave]);
    }
    public static function loginUserObservador(string $nombreUsuario, string $clave)
    {
        return DB::select("SELECT idUsuario,nombre,nombreUsuario,clave,tipoUsuario,idDispositivo FROM usuarios 
        WHERE nombreUsuario = ? AND clave = ? AND tipoUsuario = 'Observador'", [$nombreUsuario, $clave]);
    }

    public static function getMachineryByUser(int $userId)
    {
        return DB::select("CALL PA_GET_MACHINERY_BY_USER(?)", [$userId]);
    }

    public static function getUsers()
    {
        return DB::select("CALL PA_GET_ALL_USER()");
    }

}