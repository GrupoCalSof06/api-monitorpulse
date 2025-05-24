<?php
use Illuminate\Support\Facades\DB;
/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/doctores/{idUsuario}/pacientes', 'ProjectController@getPacientesByDoctor');
$router->get('/observadores/{idUsuario}/paciente', 'ProjectController@getPacienteByObservador');
$router->get('/pacientes/{idUsuario}/ultimo-historial', 'HistorialController@getUltimoHistorialPaciente');
$router->get('/pacientes/{idUsuario}/historial-completo', 'HistorialController@getHistorialCompletoPaciente');
$router->post('/login/paciente', 'UserController@loginPaciente');
$router->post('/login/doctor', 'UserController@loginDoctor');
$router->post('/login/observador', 'UserController@loginObservador');
$router->post('/historial/insertar', 'HistorialController@insertHistorial');

$router->get('/test-db', function () use ($router) {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'success' => true,
            'message' => '✅ Conexión exitosa a PostgreSQL en Render',
            'details' => [
                'host' => env('DB_HOST'),
                'database' => env('DB_DATABASE')
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => '❌ Error de conexión: ' . $e->getMessage()
        ], 500);
    }
});