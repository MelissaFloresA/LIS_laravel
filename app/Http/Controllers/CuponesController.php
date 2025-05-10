<?php

namespace App\Http\Controllers;

use App\Models\CuponesModel;
use Illuminate\Http\Request;

class CuponesController extends Controller
{
    //funcion que manda a vista la lista de cupones PENDIENTES
    public function pendientes()
    {
        $cupones = CuponesModel::pendientes()
            ->join('empresa', 'cupones.ID_Empresa', '=', 'empresa.ID_Empresa')
            ->select('cupones.*', 'empresa.Nombre as nombre_empresa')
            ->get();

        return view('cupones', [
            'titulo' => 'Cupones Pendientes',
            'cupones' => $cupones
        ]);
    }

    public function editarestado($id)
    {
        $cupon = CuponesModel::findOrFail($id);
        return view('cupones', [
            'titulo' => 'Editar Estado', //mismo div pero otro contenido por eso otro tilulo
            'cupon' => $cupon,
            'mostrarFormulario' => true //para aprobar o no con justificacion
        ]);
    }

    public function actualizarestado(Request $request, $id)
    {
        $request->validate([
            'Estado_Aprobacion' => 'required|in:Activa,Rechazado', // Cambiado a Activo en lugar de Aprobado
            'Justificacion' => 'required_if:Estado_Aprobacion,Rechazado'
        ]);

        $cupon = CuponesModel::findOrFail($id);
        $data = [
            'Estado_Aprobacion' => $request->Estado_Aprobacion,
            'Justificacion' => $request->Estado_Aprobacion == 'Rechazado' ? $request->Justificacion : null,
            'Estado_Cupon' => $request->Estado_Aprobacion == 'Activa' ? 'Disponible' : 'No disponible'
        ];

        $cupon->update($data);
        return redirect()->route('cupones.pendientes')
            ->with('success', 'Estado actualizado correctamente');
    }

}//fin de clase CuponesController
