<?php

namespace App\Http\Controllers;

use App\Models\HistorialModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HistorialController extends Controller
{

public function insertHistorial(Request $request)
{
    $this->validate($request, [
        'idUsuario' => 'required|integer',
        'pulso' => 'required|numeric',
        'spo2' => 'required|numeric|between:0,100',
        'temperatura' => 'required|numeric',
        'anomalia' => 'required|in:normal,taquicardia,bradicardia'
    ]);

    $success = HistorialModel::insertHistorial($request->all());

    if ($success) {
        return response()->json([
            'success' => true,
            'message' => 'Registro de historial creado correctamente'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Error al crear el registro'
    ], 500);
}
    
    /** Obtener último registro de historial de un paciente */
public function getUltimoHistorialPaciente($idUsuario)
{
    try {
        $historial = HistorialModel::getUltimoHistorialPaciente($idUsuario);

        return response()->json([
            'success' => true,
            'data' => $historial
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
/** Obtener todo el historial de un paciente */
public function getHistorialCompletoPaciente($idUsuario)
{
    try {
        $historial = HistorialModel::getHistorialCompletoPaciente($idUsuario);

        return response()->json([
            'success' => true,
            'data' => $historial
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Obtener detalles de una maquinaria.
     */
    public function getMachineryDetails($id)
    {
        try {
            $machineryDetails = Machinery::getMachineryDetails((int)$id);
            return response()->json($machineryDetails, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar los horómetros de una maquinaria.
     */
    public function updateHourMeters(Request $request)
    {
        // Validar los datos de entrada
        $this->validate($request, [
            'machinery_id' => 'required|integer', // ID de la maquinaria a actualizar
            'hour_A' => 'required|numeric',      // Nuevo valor para el horómetro A
            'hour_B' => 'required|numeric'       // Nuevo valor para el horómetro B
        ]);

        // Obtener los datos necesarios
        $data = $request->only(['machinery_id', 'hour_A', 'hour_B']);

        // Llamar al modelo para realizar la actualización mediante un procedimiento almacenado
        $result = MachineryModel::updateHourMeters($data);

        return response()->json([
            'message' => 'Horómetros actualizados con éxito',
            'data' => $result
        ], 200);
    }

    /**
     * Actualizar los componentes de una maquinaria.
     */
    public function updateComponentAssignments(Request $request)
    {
        // Validar los datos de entrada
        $this->validate($request, [
            'machinery_id' => 'required|integer', // ID de la maquinaria a actualizar
            'components' => 'required|array',     // Nuevos componentes a asignar
        ]);

        // Obtener los datos necesarios
        $data = $request->only(['machinery_id', 'components']);

        // Convertir el array de componentes a JSON antes de pasarlo al procedimiento almacenado
        $data['components'] = json_encode($data['components']);

        // Llamar al modelo para realizar la actualización mediante un procedimiento almacenado
        $result = MachineryModel::updateComponentAssignments($data);

        return response()->json([
            'message' => 'Componentes actualizados con éxito',
            'data' => $result
        ], 200);
    }

    public function getComponentsByMachinery(Request $request)
    {
        $this->validate($request, [
            'machinery_id' => 'required|integer'
        ]);

        $machineryId = $request->input('machinery_id');
        $result = MachineryModel::getComponentsByMachinery($machineryId);

        return response()->json($result, 200);
    }

    public function getComponentsAssignedToMachinery(Request $request)
    {
        $this->validate($request, [
            'machinery_id' => 'required|integer'
        ]);

        $machineryId = $request->input('machinery_id');
        $result = MachineryModel::getComponentsAssignedToMachinery($machineryId);

        return response()->json($result, 200);
    }

    public function getComponentsAvailable()
    {
        $result = MachineryModel::getComponentsAvailable();
        return response()->json($result, 200);
    }
    /**
     * Obtener maquinarias.
     */
    public function getMachinery()
    {
        try {
            $machineryDetails = MachineryModel::getMachinery();
            return response()->json($machineryDetails, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
