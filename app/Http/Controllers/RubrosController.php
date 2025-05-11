<?php

namespace App\Http\Controllers;

use App\Models\RubrosModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RubrosController extends Controller
{
    public function index()
    {
        $rubros = RubrosModel::orderBy('ID_Rubro', 'asc')->get(); //ordena los rubros de forma ascendente
        $titulo = 'Gestión de Rubros';

        return view('rubros', compact('rubros', 'titulo'));
    }

    //Creación
    public function store(Request $request)
    {
        $request->validate(RubrosModel::rules());

        RubrosModel::create($request->all());

        return redirect()->route('rubros.index')
            ->with('success', 'Rubro creado exitosamente.');
    }

    //Editar
    public function update(Request $request, $id)
    {
        $rubro = RubrosModel::findOrFail($id);

        $request->validate([
            'Nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\pL\s]+$/u', // Solo letras y espacios
                Rule::unique('rubros', 'Nombre')->ignore($rubro->ID_Rubro, 'ID_Rubro')
            ]
        ]);

        $rubro->update($request->all());

        return redirect()->route('rubros.index')
            ->with('success', 'Rubro actualizado exitosamente.');
    }

    //Eliminar
    public function destroy($id)
    {
        $rubro = RubrosModel::findOrFail($id);
        $rubro->delete();

        return redirect()->route('rubros.index')
            ->with('success', 'Rubro eliminado exitosamente.');
    }
}