<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HistorialModel extends Model
{
    protected $table = 'historial';

public static function insertHistorial(array $data): bool
{
    $affected = DB::insert("
        INSERT INTO historial (
            idUsuario, 
            pulso, 
            spo2, 
            temperatura, 
            anomalia
        ) VALUES (?, ?, ?, ?, ?)", [
        $data['idUsuario'],
        $data['pulso'],
        $data['spo2'],
        $data['temperatura'],
        $data['anomalia']
    ]);
    
    return $affected; // Retorna true/false
}
    public static function getUltimoHistorialPaciente(int $idUsuario): array
{
    return DB::select("SELECT 
            pulso,
            spo2,
            temperatura,
            anomalia
        FROM 
            historial
        WHERE 
            idUsuario = ?
        ORDER BY 
            horaEntrada DESC
        LIMIT 1
    ", [$idUsuario]);
}
public static function getHistorialCompletoPaciente(int $idUsuario): array
{
    return DB::select("
        SELECT 
            idHistorial,
            pulso,
            spo2,
            temperatura,
            anomalia,
            horaEntrada
        FROM 
            historial
        WHERE 
            idUsuario = ?
        ORDER BY 
            horaEntrada DESC
    ", [$idUsuario]);
}
    /**
     * Obtener detalles de una maquinaria, incluyendo componentes y horómetros.
     */
    public static function getMachineryDetails(int $machineryId): array
    {
        return DB::select('CALL PA_GET_MACHINERY_DETAILS(?)', [$machineryId]);
    }

    /**
     * Actualizar los horómetros de una maquinaria.
     */
    public static function updateHourMeters(array $data)
    {
        $params = [
            $data['machinery_id'], // ID de la maquinaria
            $data['hour_A'],       // Nuevo valor para el horómetro A
            $data['hour_B']        // Nuevo valor para el horómetro B
        ];

        // Llamar al procedimiento almacenado
        return DB::select("CALL PA_UPDATE_HOUR_METERS(?, ?, ?)", $params);
    }

    /**
     * Actualizar los componentes de una maquinaria.
     */
    public static function updateComponentAssignments(array $data)
    {
        // Preparar los parámetros
        $params = [
            $data['machinery_id'],             // ID de la maquinaria
            $data['components']                // Lista de componentes en formato JSON
        ];

        // Llamar al procedimiento almacenado
        return DB::select("CALL PA_UPDATE_COMPONENT_ASSIGNMENTS(?, ?)", $params);
    }

    public static function getComponentsByMachinery(int $machineryId)
    {
        return DB::select('CALL PA_GET_COMPONENTS_BY_MACHINERY(?)', [$machineryId]);
    }

    public static function getComponentsAssignedToMachinery(int $machineryId)
    {
        return DB::select('CALL PA_GET_COMPONENTS_ASSIGNED_TO_MACHINERY(?)', [$machineryId]);
    }

    public static function getComponentsAvailable()
    {
        return DB::select('CALL PA_GET_COMPONENTS_AVAILABLE()');
    }
  
    public static function getMachinery(): array
    {
        return DB::select('CALL PA_GET_ALL_MACHINERY()');
    }
}
