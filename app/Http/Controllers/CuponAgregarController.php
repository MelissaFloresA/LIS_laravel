<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuponAgregarModel;

class CuponAgregarController extends Controller
{
   public function crear(Request $request)
{
    if ($request->isMethod('post')) {
        $validated = $request->validate([
            'ID_Empresa' => 'required|string|max:6',
            'Titulo' => 'required|string|max:255',
            'Imagen' => 'nullable|string',
            'PrecioR' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'PrecioO' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
                'lte:PrecioR'
            ],
            'Fecha_Inicial' => 'required|date',
            'Fecha_Final' => 'required|date|after_or_equal:Fecha_Inicial',
            'Fecha_Limite' => 'required|date|after_or_equal:Fecha_Final',
            'Descripcion' => 'required|string',
            'Stock' => 'required|integer|min:1',
        ], [
            'PrecioR.regex' => 'El precio regular debe tener hasta 2 decimales (ej. 10.50)',
            'PrecioO.regex' => 'El precio de oferta debe tener hasta 2 decimales (ej. 8.75)',
            'PrecioO.lte' => 'El precio de oferta no puede ser mayor que el precio regular',
        ]);

        $cupon = new CuponAgregarModel($validated);
        $cupon->Estado_Aprobacion = 'En espera';
        $cupon->Estado_Cupon = 'No disponible';
        $cupon->Justificacion = '';
        $cupon->Cantidad_Vendidos = 0;
        $cupon->save();

        return redirect()->back()->with('success', 'Cupón agregado exitosamente.');
    }

    return view('cupones_crear');
}
       
}
