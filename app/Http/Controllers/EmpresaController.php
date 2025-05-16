<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpresaModel;
use App\Models\RubrosModel;
use Illuminate\Support\Str;
use App\Notifications\EnviarCredencialesEmpresa;




class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = EmpresaModel::all();
        $rubros = RubrosModel::all();
         $titulo = 'Gestión de Empresas';
        return view('empresa', compact('empresas', 'rubros','titulo'));
    }

    //crear
    public function store(Request $request)
    {
        $password = Str::random(8); // genera una contraseña aleatoria de 8 caracteres

        // Validaciones
        $request->validate([
            'ID_Empresa' => 'required|string|max:6|unique:empresa,ID_Empresa',
            'Nombre' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'Direccion' => 'required|string|max:100',
            'Nombre_Contacto' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'Telefono' => 'required|string|max:100',
            'Correo' => 'required|email|max:100',
            'ID_Rubro' => 'required|exists:rubros,ID_Rubro',
            'Porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        // Crear nueva empresa
        $empresa = new EmpresaModel();
        $empresa->ID_Empresa = $request->input('ID_Empresa');
        $empresa->Nombre = $request->input('Nombre');
        $empresa->Direccion = $request->input('Direccion');
        $empresa->Nombre_Contacto = $request->input('Nombre_Contacto');
        $empresa->Telefono = $request->input('Telefono');
        $empresa->Correo = $request->input('Correo');
        $empresa->ID_Rubro = $request->input('ID_Rubro');
        $empresa->Porcentaje_Comision = $request->input('Porcentaje');

        if ($empresa->save()) {
          
     $empresa->notify(new \App\Notifications\EnviarCredencialesEmpresa($password));

            return redirect()->route('empresa.index')->with('success', 'Empresa creada exitosamente.');
            
        } else {
            return redirect()->back()->with('error', 'Error al crear la empresa.');
        }
    }
    //editar
    public function update(Request $request, $id){

        $empresa = EmpresaModel::findOrFail($id);
        $request->validate([
            'ID_Empresa' => 'required|string|max:6|unique:empresa,ID_Empresa,' . $empresa->ID_Empresa . ',ID_Empresa',
            'Nombre' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'Direccion' => 'required|string|max:100|regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s.,#\-º°\/()]+$/',

            'Nombre_Contacto' => 'required|string|max:100|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/',
            'Telefono' => 'required|string|max:100',
            'Correo' => 'required|email|max:100',
            'ID_Rubro' => 'required|exists:rubros,ID_Rubro',
            'Porcentaje' => 'required|numeric|min:0|max:100',
        ]);
        
        $empresa->fill($request->except('Porcentaje')); // llena lo demás
$empresa->Porcentaje_Comision = $request->Porcentaje;
$empresa->save();

        return redirect()->route('empresa.index')->with('success', 'Empresa actualizada exitosamente.');
    }

    //eliminar
    public function destroy($id)
    {
        $empresa = EmpresaModel::findOrFail($id);
        $empresa->delete();

        return redirect()->route('empresa.index')->with('success', 'Empresa eliminada exitosamente.');
    }
   
   
}
