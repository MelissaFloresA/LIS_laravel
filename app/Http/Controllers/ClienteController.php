<?php

namespace App\Http\Controllers;

use App\Models\ClienteModel;
use App\Models\VentaModel;
use Carbon\Carbon;

class ClienteController extends Controller
{
    // lista de clientes

    public function index()
    {
        $clientes = ClienteModel::select([
            'ID_Cliente',
            'Nombre',
            'Apellido',
            'Telefono',
            'Correo',
            'Direccion'
        ])->orderBy('Nombre')->get();

        return view('clientes', compact('clientes'));
    }


    //Muestra cupones de un cliente 

    public function showCupones($id)
    {
        $cliente = ClienteModel::findOrFail($id);

        $ventas = VentaModel::where('ID_Cliente', $id)
            ->with([
                'cupon' => function ($query) {
                    $query->select([
                        'ID_Cupon',
                        'Titulo',
                        'Imagen',
                        'PrecioO',
                        'PrecioR',
                        'Descripcion',
                        'Fecha_Final',
                        'Estado_Cupon'
                    ]);
                }
            ])
            ->get();

        $fechaActual = Carbon::now();

        $cupones = [
            'disponibles' => [],
            'canjeados' => [],
            'vencidos' => []
        ];

        foreach ($ventas as $venta) {
            if (!$venta->cupon)
                continue;

            $cuponData = [
                'ID_Venta' => $venta->ID_Venta,
                'ID_Cupon' => $venta->ID_Cupon,
                'Titulo' => $venta->cupon->Titulo,
                'Imagen' => $venta->cupon->Imagen,
                'PrecioO' => $venta->cupon->PrecioO,
                'PrecioR' => $venta->cupon->PrecioR,
                'Descripcion' => $venta->cupon->Descripcion,
                'Fecha_Final' => $venta->cupon->Fecha_Final,
                'Fecha_Compra' => $venta->Fecha_Compra,
                'Veces_Canje' => $venta->Veces_Canje,
                'Cantidad' => $venta->Cantidad,
                'Estado_Cupon' => $venta->cupon->Estado_Cupon,
                'Codigo_Cupon' => $venta->Codigo_Cupon
            ];

            $fechaFinal = Carbon::parse($venta->cupon->Fecha_Final);

            // Cupones vencidos (no canjeados y fecha pasada)
            if ($venta->Veces_Canje == 0 && $fechaFinal->lt($fechaActual)) {
                $cupones['vencidos'][] = $cuponData;
            }
            // Cupones canjeados (al menos 1 canje)
            elseif ($venta->Veces_Canje >= 1) {
                $cupones['canjeados'][] = $cuponData;
            }
            // Cupones disponibles
            else {
                $cupones['disponibles'][] = $cuponData;
            }
        }

        return view('clientes_cupones', [
            'cliente' => $cliente,
            'cupones' => $cupones
        ]);
    }
}