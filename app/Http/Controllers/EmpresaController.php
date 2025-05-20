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




class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = EmpresaModel::where('ID_Empresa', '!=', 'CUPON')->get();
        return view('empresa', compact('empresas'));
    }

    //crear
    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            $rubros = RubrosModel::where('ID_Rubro', '!=', '1')->get();
            return view('empresa_form', compact('rubros'));
        } else if ($request->isMethod('post')) {
            $password = Str::random(8);

            $request->validate([
                'Nombre' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                'Direccion' => 'required|string|max:100|regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s.,#\-º°\/()]+$/',
                'Nombre_Contacto' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                'Telefono' => 'required|string|max:100',
                'Correo' => 'required|email|max:100',
                'ID_Rubro' => 'required|exists:rubros,ID_Rubro',
                'Porcentaje' => 'required|numeric|min:0|max:100',
            ]);

            // Verificar si ya existe un representante con ese correo
            $yaExiste = EmpleadosModel::where('Correo', $request->Correo)->exists();

            if ($yaExiste) {
                return back()->withInput()->with('error', 'El correo del representante ya existe. Usa otro correo.');
            }

            try {
                DB::table('empresa')->insert([
                    'Nombre' => $request->Nombre,
                    'Direccion' => $request->Direccion,
                    'Nombre_Contacto' => $request->Nombre_Contacto,
                    'Telefono' => $request->Telefono,
                    'Correo' => $request->Correo,
                    'ID_Rubro' => $request->ID_Rubro,
                    'Porcentaje_Comision' => $request->Porcentaje,
                ]);

                // Recuperar la empresa recién creada
                $empresa = DB::table('empresa')
                    ->where('Correo', $request->Correo)
                    ->orderBy('ID_Empresa', 'desc')
                    ->first();

                $idEmpresa = $empresa->ID_Empresa;

                // Luego insertar representante con ese id
                DB::insert(
                    "INSERT INTO empleados (ID_Empresa, Nombre, Correo, Contrasena, ID_Rol, Estado) VALUES (?, ?, ?, ?, ?, ?)",
                    [
                        $idEmpresa,
                        $request->Nombre_Contacto,
                        $request->Correo,
                        Hash::make($password),
                        1,
                        1
                    ]
                );

                //enviar correo
                $representante = EmpleadosModel::where('Correo', $request->Correo)->first();
                $representante->notify(new EnviarCredencialesEmpresa($password));

                DB::commit();

                return redirect()->route('empresas.index')->with('success', 'Empresa y representante creados exitosamente');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error en creación de empresa/representante: " . $e->getMessage());
                return back()->withInput()->with('error', 'Error al crear registros: ' . $e->getMessage());
            }
        }
    }
    //editar
    public function update(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            if ($id == 'CUPON') {
                return redirect()->route('empresas.index');
            }
            $empresa = EmpresaModel::findOrFail($id);
            $rubros = RubrosModel::where('ID_Rubro', '!=', '1')->get();
            return view('empresa_form', compact('empresa', 'rubros'));
        } else if ($request->isMethod('put')) {
            $request->validate([
                'Nombre' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                'Direccion' => 'required|string|max:100|regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s.,#\-º°\/()]+$/',
                'Nombre_Contacto' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
                'Telefono' => 'required|string|max:100',
                'Correo' => 'required|email|max:100',
                'ID_Rubro' => 'required|exists:rubros,ID_Rubro',
                'Porcentaje' => 'required|numeric|min:0|max:100',
            ]);

            DB::beginTransaction();

            try {

                $empresa = EmpresaModel::findOrFail($id);

                // Guardar valores antiguos para comparar correo representante
                $correoAntiguo = $empresa->Correo;

                // Actualizar datos de empresa
                $empresa->Nombre = $request->Nombre;
                $empresa->Direccion = $request->Direccion;
                $empresa->Nombre_Contacto = $request->Nombre_Contacto;
                $empresa->Telefono = $request->Telefono;
                $empresa->Correo = $request->Correo;
                $empresa->ID_Rubro = $request->ID_Rubro;
                $empresa->Porcentaje_Comision = $request->Porcentaje;
                $empresa->save();

                // Obtener representante relacionado a esta empresa
                $representante = EmpleadosModel::where('ID_Empresa', $id)
                    ->where('ID_Rol', 1)
                    ->first();

                if (!$representante) {
                    throw new \Exception("Representante no encontrado para la empresa $id");
                }

                // Verificar si el correo cambió
                if ($correoAntiguo !== $request->Correo) {

                    // Generar nueva contraseña
                    $nuevaPassword = Str::random(8);

                    $representante->Correo = $request->Correo;
                    $representante->Nombre = $request->Nombre_Contacto;
                    $representante->Estado = 1; // reactivar representante
                    $representante->Contrasena = Hash::make($nuevaPassword);
                    $representante->save();

                    // Enviar correo con la nueva contraseña
                    $representante->notify(new EnviarCredencialesEmpresa($nuevaPassword));
                } else {
                    // Solo actualizar nombre contacto 
                    $representante->Nombre = $request->Nombre_Contacto;
                    $representante->save();
                }

                DB::commit();

                return redirect()->route('empresas.index')->with('success', 'Empresa y representante actualizados correctamente');
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error("Error al actualizar empresa y representante: " . $e->getMessage());
                return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
            }
        }
    }


    //eliminar


    public function destroy($id)
    {
        $empresa = EmpresaModel::findOrFail($id);

        // Asegúrate que el ID sea exactamente igual
        EmpleadosModel::where('ID_Empresa', $id)->delete();

        $empresa->delete();

        return redirect()->route('empresas.index')->with('success', 'Empresa y sus representantes eliminados.');
    }
}
