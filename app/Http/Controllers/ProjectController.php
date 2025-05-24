<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /** Obtener pacientes asignados a un doctor */
    public function getPacientesByDoctor($idUsuario)
{
    try {
        $pacientes = ProjectModel::getPacientesByDoctor($idUsuario);

        return response()->json([
            'success' => true,
            'data' => $pacientes
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
/** Obtener paciente asignado a un observador */
public function getPacienteByObservador($idUsuario)
{
    try {
        $paciente = ProjectModel::getPacienteByObservador($idUsuario);

        return response()->json([
            'success' => true,
            'data' => $paciente
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function createProjectWithAssignments(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'created_by' => 'required|integer',
            'users' => 'required|array',
            'machinery' => 'required|array',
        ]);
       
        $data = $request->only(['name', 'created_by', 'users', 'machinery']);
        
        $result = ProjectModel::createProjectWithAssignments($data);

        return response()->json(['message' => 'Proyecto creado con éxito', 'data' => $result], 201);
    }

    public function updateProject(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',  // ID del proyecto a actualizar
            'name' => 'required|string', // Nuevo nombre del proyecto
            'created_by' => 'required|integer', // ID del creador
            'users' => 'required|array', // Nuevos usuarios asignados
            'machinery' => 'required|array' // Nuevas maquinarias asignadas
        ]);
    
        $data = $request->only(['id', 'name', 'created_by', 'users', 'machinery']);
    
        $result = ProjectModel::updateProjectWithAssignments($data);
    
        return response()->json(['message' => 'Proyecto actualizado con éxito', 'data' => $result], 200);
    }

    public function deleteProject(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer' // ID del proyecto a eliminar
        ]);
    
        $id = $request->input('id');
    
        $result = ProjectModel::deleteProject($id);
    
        return response()->json(['message' => 'Proyecto eliminado con éxito', 'data' => $result], 200);
    }
public function getProjects()
{
    try {
        $projects = ProjectModel::getProjects();

        return response()->json($projects, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
/** Obtener todos los proyectos */
public function getAllProjects()
{
    $projects = ProjectModel::getAllProjects();
    return response()->json($projects);
}

/** Obtener un proyecto por su ID */
public function getProjectById($id)
{
    $project = ProjectModel::getProjectById($id);
    return response()->json($project);
}

/** Filtrar proyectos por criterios */
public function filterProjects(Request $request)
{
    $data = $request->only(['name', 'created_by', 'start_date', 'end_date']);
    $projects = ProjectModel::filterProjects($data);
    return response()->json($projects);
}

}