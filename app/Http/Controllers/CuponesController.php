<?php

namespace App\Http\Controllers;

use App\Models\CuponesModel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\EmpresaModel;
use Carbon\Carbon;  

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
   /*public function filtrarPorEmpresa(Request $request)
{
     $empresas = EmpresaModel::all();
    $cupones = [];

    if ($request->has('empresa_id') && $request->filled('empresa_id')) {
        $cupones = DB::table('cupones as c')
            ->join('empresa as e', 'c.ID_Empresa', '=', 'e.ID_Empresa')
            ->select('e.Nombre', 'c.Imagen', 'c.Titulo', 'c.PrecioR', 'c.PrecioO', 'c.Cantidad_Vendidos', 'c.Estado_Aprobacion', 'c.ESTADO_CUPON', 'e.Porcentaje_Comision')
            ->where('c.ID_Empresa', $request->empresa_id)
            ->get();
    }

    return view('empresa_cupones', compact( 'empresas','cupones'));
}
public function filtroEmpresa()
{
    $empresas = EmpresaModel::all(); // obtiene todas las empresas
    return view('empresa_cupones', compact('empresas'));  // PASA $empresas a la vista
}*/


public function filtrarPorEmpresa(Request $request)
{
    $empresaId = $request->input('empresa_id');
    $hoy = Carbon::today()->toDateString();
     



      if (!$empresaId) {
    return view('empresa_cupones', [
        'empresas' => EmpresaModel::all(),
        'cuponesEnEspera' => [],
        'cuponesAprobadosFuturos' => [],
        'cuponesActivos' => [],
        'cuponesPasados' => [],
        'cuponesRechazados' => [],
        'cuponesDescartados' => [],
        'empresaId' => null, // 
    ]);
}
    // Cupones en espera (Estado_Aprobacion = 'En espera')
    $cuponesEnEspera = DB::table('cupones as c')
        ->join('empresa as e', 'c.ID_Empresa', '=', 'e.ID_Empresa')
        ->select('c.*', 'e.Nombre', 'e.Porcentaje_Comision', 'c.Fecha_Inicial', 'c.Fecha_Final')
        ->where('c.ID_Empresa', $empresaId)
        ->where('c.Estado_Aprobacion', 'En espera')
        ->get();

    // Cupones aprobados futuros (aprobados pero con fecha inicio > hoy)
    $cuponesAprobadosFuturos = DB::table('cupones as c')
        ->join('empresa as e', 'c.ID_Empresa', '=', 'e.ID_Empresa')
        ->select('c.*', 'e.Nombre', 'e.Porcentaje_Comision', 'c.Fecha_Inicial', 'c.Fecha_Final')
        ->where('c.ID_Empresa', $empresaId)
        ->where('c.Estado_Aprobacion', 'En espera')
        ->whereDate('c.Fecha_Inicial', '>', $hoy)
        ->get();

    // Cupones activos (aprobados y dentro del rango de fecha)
    $cuponesActivos = DB::table('cupones as c')
        ->join('empresa as e', 'c.ID_Empresa', '=', 'e.ID_Empresa')
        ->select('c.*', 'e.Nombre', 'e.Porcentaje_Comision', 'c.Fecha_Inicial', 'c.Fecha_Final')
        ->where('c.ID_Empresa', $empresaId)
        ->where('c.Estado_Aprobacion', 'Activa')
        ->whereDate('c.Fecha_Inicial', '<=', $hoy)
        ->whereDate('c.Fecha_Final', '>=', $hoy)
        ->get();

    // Cupones pasados (aprobados pero con fecha fin < hoy)
    $cuponesPasados = DB::table('cupones as c')
        ->join('empresa as e', 'c.ID_Empresa', '=', 'e.ID_Empresa')
        ->select('c.*', 'e.Nombre', 'e.Porcentaje_Comision', 'c.Fecha_Inicial', 'c.Fecha_Final')
        ->where('c.ID_Empresa', $empresaId)
        ->where('c.Estado_Aprobacion', 'Activa')
        ->whereDate('c.Fecha_Final', '<', $hoy)
        ->get();

    // Cupones rechazados
    $cuponesRechazados = DB::table('cupones as c')
        ->join('empresa as e', 'c.ID_Empresa', '=', 'e.ID_Empresa')
        ->select('c.*', 'e.Nombre', 'e.Porcentaje_Comision')
        ->where('c.ID_Empresa', $empresaId)
        ->where('c.Estado_Aprobacion', 'Rechazado')
        ->get();

    // Cupones descartados (si tienes otro estado para descartados, p.ej. 'Descartado')
    $cuponesDescartados = DB::table('cupones as c')
        ->join('empresa as e', 'c.ID_Empresa', '=', 'e.ID_Empresa')
        ->select('c.*', 'e.Nombre', 'e.Porcentaje_Comision')
        ->where('c.ID_Empresa', $empresaId)
        ->where('c.Estado_Aprobacion', 'Descartado') // Cambia si usas otro valor
        ->get();

    $empresas = EmpresaModel::all();
    $cuponesPorCategoria = [
    'activa' => $cuponesActivos,
    'espera' => $cuponesEnEspera,
    'aprobadas_futuras' => $cuponesAprobadosFuturos,
    'pasadas' => $cuponesPasados,
    'rechazadas' => $cuponesRechazados,
    'descartadas' => $cuponesDescartados,
];

    return view('empresa_cupones', compact(
        'empresaId',
        'empresas',
        'cuponesEnEspera',
        'cuponesAprobadosFuturos',
        'cuponesActivos',
        'cuponesPasados',
        'cuponesRechazados',
        'cuponesDescartados',
        'cuponesPorCategoria' 
    ));
}





}//fin de clase CuponesController
