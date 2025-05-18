<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepresentantesModel;
use App\Models\EmpresaModel;

class VerificacionController extends Controller
{
    public function verificar($id)
    {
        $representante = RepresentantesModel::find($id);

        if (!$representante) {
            return redirect('/')->with('error', 'Cuenta no encontrada.');
        }

        // Solo si no está ya validado
        if ($representante->Estado == 1) {//estado 1= no validado
            $representante->Estado = 0; //estado 0= validado
            $representante->save();
        }

        return redirect('/')->with('success', 'Cuenta verificada exitosamente.'); // redireccionar a la página de inicio de sesion ya que su estado paso a 0
    }
}
