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
    public function index(Request $request)
    {
        $estado = $request->query('estado', 'pendiente');
        $cupones = CuponesModel::where('cupones.ID_Empresa', session('ID_Empresa'))
            ->where('Estado_Aprobacion', $estado)
            ->join('empresa', 'cupones.ID_Empresa', '=', 'empresa.ID_Empresa')
            ->select('cupones.*', 'empresa.Nombre as nombre_empresa')
            ->get();

        $empresa = session('ID_Empresa');
        $nombre_empresa = EmpresaModel::where('ID_Empresa', session('ID_Empresa'))->value('Nombre');

        return view('cupones', compact('cupones', 'estado', 'nombre_empresa', 'empresa'));
    }

    public function indexEmpresa($empresa, Request $request)
    {
        //Middleware de ruta compartida
        if (strtoupper($empresa) == 'CUPON') {
            return redirect()->route('empresas.index');
        }

        $nombre_empresa = EmpresaModel::where('ID_Empresa', $empresa)->value('Nombre');

        $estado = $request->query('estado', 'pendiente');
        $cupones = CuponesModel::where('cupones.ID_Empresa', $empresa)
            ->where('Estado_Aprobacion', $estado)
            ->join('empresa', 'cupones.ID_Empresa', '=', 'empresa.ID_Empresa')
            ->select('cupones.*', 'empresa.Nombre as nombre_empresa')
            ->get();

        return view('cupones', compact('cupones', 'estado', 'nombre_empresa', 'empresa'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
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

            $validated['ID_Empresa'] = session('ID_Empresa');

            $cupon = new CuponesModel($validated);
            $cupon->Estado_Aprobacion = 'Pendiente';
            $cupon->Estado_Cupon = 'No disponible';
            $cupon->Justificacion = '';
            $cupon->Cantidad_Vendidos = 0;
            $cupon->save();

            return redirect()->route('cupones.index')->with('success', 'Cupón agregado exitosamente.');
        }

        return view('cupones_form');
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('put')) {
            $validated = $request->validate([
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

            $cupon = CuponesModel::findOrFail($id);
            $validated['Estado_Aprobacion'] = 'Pendiente';
            $cupon->update($validated);

            return redirect()->route('cupones.index')->with('success', 'Cupón actualizado exitosamente.');
        }

        $cupon = CuponesModel::findOrFail($id);
        return view('cupones_form', compact('cupon'));
    }

    public function aprobar(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $cupon = CuponesModel::findOrFail($id);
            return view('cupones_aprobar', compact('cupon'));
        } else if ($request->isMethod('put')) {
            $request->validate([
                'Estado_Aprobacion' => 'required|in:Activa,Rechazada',
                'Justificacion' => 'required_if:Estado_Aprobacion,Rechazada'
            ]);

            $cupon = CuponesModel::findOrFail($id);
            $data = [
                'Estado_Aprobacion' => $request->Estado_Aprobacion,
                'Justificacion' => $request->Estado_Aprobacion == 'Rechazada' ? $request->Justificacion : null,
                'Estado_Cupon' => $request->Estado_Aprobacion == 'Activa' ? 'Disponible' : 'No disponible'
            ];

            $cupon->update($data);
            return redirect()->route('empresa.cupones.index', $cupon->ID_Empresa)
                ->with('success', 'Estado actualizado correctamente');
        }
    }

    public function destroy($id)
    {
        $cupon = CuponesModel::findOrFail($id);
        $cupon->Estado_Aprobacion = 'descartada';
        $cupon->save();
        return redirect()->route('cupones.index')->with('success', 'Cupón eliminado exitosamente.');
    }

    public function canjear(Request $request, $id = null)
    {
        if ($request->isMethod('get')) {
            if ($id) {
                $cupon = DB::table('ventas')
                    ->join('cupones', 'ventas.ID_Cupon', '=', 'cupones.ID_Cupon')
                    ->join('cliente', 'ventas.ID_Cliente', '=', 'cliente.ID_Cliente')
                    ->where('Codigo_Cupon', $id)
                    ->select('cupones.*', 'ventas.*', 'cliente.*')
                    ->first();
                return view('cupones_canjear', compact('cupon'));
            }
            return view('cupones_canjear');
        } else if ($request->isMethod('post')) {
            $validated = $request->validate([
                'Codigo_Cupon' => 'required|string',
            ]);

            if (!DB::table('ventas')->where('Codigo_Cupon', $validated['Codigo_Cupon'])->exists()) {
                return redirect()->route('cupones.canjear')->with('error', 'Cupon no encontrado');
            }
            if (!DB::table('ventas')
                ->join('cupones', 'ventas.ID_Cupon', '=', 'cupones.ID_Cupon')
                ->where('Codigo_Cupon', $validated['Codigo_Cupon'])
                ->where('Estado_Aprobacion', 'Activa')
                ->where('Estado_Cupon', 'Disponible')
                ->where('Veces_Canje', '<', DB::raw('Cantidad'))
                ->exists()) {
                return redirect()->route('cupones.canjear')->with('error', 'Cupon no disponible para canjear');
            }

            try {
                DB::table('ventas')
                    ->where('Codigo_Cupon', $validated['Codigo_Cupon'])
                    ->increment('Veces_Canje');
            } catch (\Exception $e) {
                return redirect()->route('cupones.canjear')->with('error', 'Cupon no encontrado');
            }
            return redirect()->route('cupones.canjear')->with('success', 'Cupon canjeado exitosamente');
        }
    }
}
