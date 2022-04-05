<?php

namespace App\Http\Controllers;

use App\Models\BCamaraModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraficosController extends Controller
{
    public function index()
    {

        if (isset($_POST['fecha_actual']) && !empty($_POST['fecha_actual'])) {
            $fecha = $_POST['fecha_actual'];
        } else {
            $fecha = date('Y-m-d');
        }

        if (isset($_POST['kmara']) && !empty($_POST['kmara'])) {
            $kmaraSelect = $_POST['kmara'];
            $datos = $this->getDatosGrafico($fecha, $kmaraSelect);
        } else {
            $kmaraSelect = '';
            $datos = $this->getDatosGrafico($fecha);
        }

        
        $entradas = [];
        $salidas = [];
        $kmara = BCamaraModel::all(); 

        foreach ($datos as $dato) {
            if (strtolower($dato->orientacion) == 'salida') {
                array_push($salidas, $dato);
            } else {
                array_push($entradas, $dato);
            }
        }
        $datosCompletos = [];
        $validaFechaHora = [];
        if (empty($entradas) && empty($salidas)) {
            $datosCompletos = [];
        } else {
            foreach ($entradas as $e) {
                $resultado = $this->validaTipo($e->fecha . $e->hora, $salidas);
                if ($resultado != 0) {
                    array_push($validaFechaHora, $e->fecha . $e->hora);
                    $data = [
                        'fecha_entrada' => $e->fecha,
                        'hora_entrada' => $e->hora,
                        'cantidad_entrada' => $e->cantidad,
                        'fecha_salida' => $resultado['fecha'],
                        'hora_salida' => $resultado['hora'],
                        'cantidad_salida' => $resultado['cantidad'],
                    ];
                    array_push($datosCompletos, $data);
                } else {
                    $data = [
                        'fecha_entrada' => $e->fecha,
                        'hora_entrada' => $e->hora,
                        'cantidad_entrada' => $e->cantidad,
                        'fecha_salida' => $e->fecha,
                        'hora_salida' => $e->hora,
                        'cantidad_salida' => 0
                    ];
                    array_push($datosCompletos, $data);
                }
            }

            foreach ($salidas as $s) {
                $resultado = $this->validaTipo($s->fecha . $s->hora, $entradas);
                if ($resultado != 0) {
                    $data = [
                        'fecha_entrada' => $resultado['fecha'],
                        'hora_entrada' => $resultado['hora'],
                        'cantidad_entrada' => $resultado['cantidad'],
                        'fecha_salida' => $s->fecha,
                        'hora_salida' => $s->hora,
                        'cantidad_salida' => $s->cantidad,
                    ];
                    array_push($datosCompletos, $data);
                    array_push($validaFechaHora, $s->fecha . $s->hora);
                } else {
                    $data = [
                        'fecha_entrada' => $s->fecha,
                        'hora_entrada' => $s->hora,
                        'cantidad_entrada' => 0,
                        'fecha_salida' => $s->fecha,
                        'hora_salida' => $s->hora,
                        'cantidad_salida' => $s->cantidad,
                    ];
                    array_push($datosCompletos, $data);
                    array_push($validaFechaHora, $s->fecha . $s->hora);
                }
            }
        }


        return view('domotica.bcamara', compact('fecha', 'datosCompletos', 'kmara', 'kmaraSelect'));
    }

    public function validaTipo($fechahora, $buscar)
    {

        foreach ($buscar as $busca) {
            if ($busca->fecha . $busca->hora == $fechahora) {
                $data = [
                    'fecha' => $busca->fecha,
                    'hora' => $busca->hora,
                    'cantidad' => $busca->cantidad,
                ];
                return $data;
            }
        }
        return 0;
    }



    function getDatosGrafico($fecha, $kmara = null)
    {
        if(empty($kmara))
        {
            $AndWhere = '';
        }else{
            $AndWhere = ' AND vc.id_cameraIndexCode = '. $kmara;
        }
        if (empty($fecha)) {
            $fecha = date('Y-m-d');
        }
        $grafico = DB::SELECT('
                    SELECT DATE_FORMAT(dv.crossTime, "%d-%m-%Y") fecha,  DATE_FORMAT(dv.crossTime, "%H") hora,
                    CASE
                        WHEN vc.camara = 1 THEN "Salida"
                        WHEN vc.camara = 2 THEN "Entrada"
                    END orientacion,
                    COUNT(dv.cameraIndexCode) cantidad
                    FROM detalle_vehiculos dv
                    JOIN valorcamara vc
                    ON vc.id_cameraIndexCode = dv.cameraIndexCode
                    WHERE dv.crossTime LIKE "%' . $fecha . '%" '. $AndWhere.'
                    GROUP BY fecha, hora, orientacion
                            ');
        return $grafico;
    }

    function pre_die($array = array())
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        die();
    }
    function pre($array = array())
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}
