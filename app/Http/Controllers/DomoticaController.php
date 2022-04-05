<?php

namespace App\Http\Controllers;

use App\Models\DomoticaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomoticaController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function camara()
    {
        return view('layouts.camara');
    }

    public function equipo()
    {
        return view('layouts.equipo');
    }

    public function persona()
    {
        return view('layouts.personas');
    }
    public function vehiculo()
    {
        $vehiculos = DB::SELECT('SELECT LEFT(dv.crossTime, 10) Fecha,
        CASE
            WHEN vc.camara = 1 THEN "Salida"
            WHEN vc.camara = 2 THEN "Entrada"
        END Orientacion,
        COUNT(dv.cameraIndexCode) Cantidad
        FROM detalle_vehiculos dv
        JOIN valorcamara vc
        ON vc.id_cameraIndexCode = dv.cameraIndexCode
        GROUP BY Fecha, Orientacion');
        return view('domotica.vehiculos', compact('vehiculos'));
    }
}
