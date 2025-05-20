<?php

namespace App\Http\Controllers;

use App\Models\EmpleadosModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\EmpresaModel;
use App\Models\RubrosModel;
use Illuminate\Support\Str;
use App\Notifications\EnviarCredencialesEmpresa;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;




class EmpleadosController extends Controller
{
    public function index()
    {
        $empleados = EmpleadosModel::where('ID_Empresa', session('ID_Empresa'))
        ->join('roles', 'empleados.ID_Rol', '=', 'roles.ID_Rol')
        ->select('empleados.*', 'roles.Nombre as Nombre_Rol')
        ->get();
        return view('empleado', compact('empleados'));
    }

    //crear
    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('empleado_form');
        } else if ($request->isMethod('post')) {
            $password = Str::random(8);

            $validated = $request->validate([
                'Nombre' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                'Correo' => 'required|email|max:100',
                'ID_Rol' => 'required|exists:roles,ID_Rol',
            ]);

            $validated['ID_Empresa'] = session('ID_Empresa');
            $validated['Contrasena'] = Hash::make($password);
            $validated['Estado'] = 1;

            // Verificar si ya existe un empleado con ese correo
            $yaExiste = EmpleadosModel::where('Correo', $request->Correo)->exists();

            if ($yaExiste) {
                return back()->withInput()->with('error', 'Ese correo ya existe. Usa otro correo.');
            }

            DB::beginTransaction();
            try {
                DB::table('empleados')->insert($validated);

                //enviar correo
                $empleado = EmpleadosModel::where('Correo', $request->Correo)->first();
                $empleado->notify(new EnviarCredencialesEmpresa($password));

                DB::commit();

                return redirect()->route('empleados.index')->with('success', 'Empleado creado exitosamente');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error en creación de empleado: " . $e->getMessage());
                return back()->withInput()->with('error', 'Error al crear registros: ' . $e->getMessage());
            }
        }
    }
    //editar
    public function update(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $empleado = EmpleadosModel::findOrFail($id);
            return view('empleado_form', compact('empleado'));
        } else if ($request->isMethod('put')) {
            $request->validate([
                'Nombre' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                'Correo' => 'required|email|max:100',
                'ID_Rol' => 'required|exists:roles,ID_Rol',
            ]);

            DB::beginTransaction();

            try {
                // Obtener representante relacionado a esta empresa
                $representante = EmpleadosModel::where('ID_Empleado', $id)
                    ->where('ID_Empresa', session('ID_Empresa'))
                    ->first();

                if (!$representante) {
                    throw new \Exception("Empleado no encontrado para la empresa $id");
                }

                // Verificar si el correo cambió
                if ($representante->Correo !== $request->Correo) {

                    // Generar nueva contraseña
                    $nuevaPassword = Str::random(8);
                    $representante->Correo = $request->Correo;
                    $representante->Nombre = $request->Nombre;
                    $representante->Estado = 1; 
                    $representante->ID_Rol = $request->ID_Rol;
                    $representante->Contrasena = Hash::make($nuevaPassword);
                    $representante->save();

                    // Enviar correo con la nueva contraseña
                    $representante->notify(new EnviarCredencialesEmpresa($nuevaPassword));
                } else {
                    $representante->Nombre = $request->Nombre;
                    $representante->ID_Rol = $request->ID_Rol;
                    $representante->Estado = 1; 
                    $representante->save();
                }

                DB::commit();

                return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente');
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error("Error al actualizar empleado: " . $e->getMessage());
                return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
            }
        }
    }

    public function destroy($id)
    {   
        if(session(('ID_Empleado')) == $id){
            return redirect()->route('empleados.index')->with('error', 'No puedes desactivar tu propio perfil.');
        }
        $empleado = EmpleadosModel::findOrFail($id);
        $empleado->Estado = 0; 
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado desactivado exitosamente.');
    }
}
