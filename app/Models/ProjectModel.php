<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectModel extends Model
{
    public static function getPacientesByDoctor(int $idUsuario)
{
    return DB::select("SELECT 
    p.idUsuario,
    p.nombre AS nombre_paciente,
    p.nombreUsuario AS usuario_paciente,
    d.nombre AS nombre_doctor
FROM 
    relaciones r
JOIN 
    usuarios p ON r.idUsuarioPaciente = p.idUsuario
JOIN 
    usuarios d ON r.idUsuarioAsignado = d.idUsuario
WHERE 
    d.idUsuario = ?
    AND d.tipoUsuario = 'Doctor';", [$idUsuario]);
}
public static function getPacienteByObservador(int $idUsuario): array
{
    return DB::select("SELECT 
            p.idUsuario,
            p.nombre AS nombre_paciente,
            p.nombreUsuario AS usuario_paciente,
            o.nombre AS nombre_observador
        FROM 
            relaciones r
        JOIN 
            usuarios p ON r.idUsuarioPaciente = p.idUsuario
        JOIN 
            usuarios o ON r.idUsuarioAsignado = o.idUsuario
        WHERE 
            o.idUsuario = ?
            AND o.tipoUsuario = 'Observador'
    ", [$idUsuario]);
}

    public static function createProjectWithAssignments(array $data)
    {
        $params = [
            $data['name'],                  // Nombre del proyecto
            $data['created_by'],            // ID del creador
            json_encode($data['users']),    // Convertir `users` a JSON
            json_encode($data['machinery']) // Convertir `machinery` a JSON
        ];

        return DB::select("CALL PA_CREATE_PROJECT_WITH_ASSIGNMENTS(?, ?, ?, ?)", $params);
    }

    public static function updateProjectWithAssignments(array $data)
{
    $params = [
        $data['id'],                     // ID del proyecto
        $data['name'],                   // Nuevo nombre del proyecto
        $data['created_by'],             // ID del creador
        json_encode($data['users']),     // Convertir `users` a JSON
        json_encode($data['machinery'])  // Convertir `machinery` a JSON
    ];

    return DB::select("CALL PA_UPDATE_PROJECT_WITH_ASSIGNMENTS(?, ?, ?, ?, ?)", $params);
}

    

    public static function getProjects(): array
    {
        return DB::select("CALL PA_OBTENER_PROYECTOS()");
    }
    public static function getAllProjects(): array
    {
        return DB::select("CALL PA_GET_ALL_PROJECTS()");
    }

    public static function getProjectById(int $id): array
    {
        return DB::select("CALL PA_GET_PROJECT_BY_ID(?)", [$id]);
    }

    public static function filterProjects(array $data): array
    {
        $params = [
            $data['name'] ?? null,
            $data['created_by'] ?? null,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
        ];
        return DB::select("CALL PA_FILTER_PROJECTS(?, ?, ?, ?)", $params);
    }
}