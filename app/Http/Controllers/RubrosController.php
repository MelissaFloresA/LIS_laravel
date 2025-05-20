<?php

namespace App\Http\Controllers;

use App\Models\RubrosModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\EmpresaModel;


class RubrosController extends Controller
{
    public function index()
    {
        $rubros = RubrosModel::where('ID_Rubro', '!=', '1')->orderBy('ID_Rubro', 'asc')->get(); //ordena los rubros de forma ascendente
        $titulo = 'Gestión de Rubros';

        return view('rubros', compact('rubros', 'titulo'));
    }

    //Creación
    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('rubros_form');
        } else if ($request->isMethod('post')) {
            $request->validate(RubrosModel::rules());

            RubrosModel::create($request->all());

            return redirect()->route('rubros.index')
                ->with('success', 'Rubro creado exitosamente.');
        }
    }

    //Editar
    public function update(Request $request, $id)
    {
        if ($id == 1) {
            return redirect()->route('rubros.index');
        }
        if ($request->isMethod('get')) {
            $rubro = RubrosModel::findOrFail($id);
            return view('rubros_form', compact('rubro'));
        } else if ($request->isMethod('put')) {
            $rubro = RubrosModel::findOrFail($id);

            $request->validate(RubrosModel::rules($id));

            $rubro->update($request->all());

            return redirect()->route('rubros.index')
                ->with('success', 'Rubro actualizado exitosamente.');
        }
    }

    //Eliminar
    public function destroy($id)
    {
        try {
            $rubro = RubrosModel::findOrFail($id);
            
            // Verificar si hay empresas relacionadas
            $empresasRelacionadas = EmpresaModel::where('ID_Rubro', $id)->count();
            
            if ($empresasRelacionadas > 0) {
                return redirect()->route('rubros.index')
                    ->with('error', 'No se puede eliminar el rubro porque tiene empresas asociadas.');
            }
            
            $rubro->delete();
            
            return redirect()->route('rubros.index')
                ->with('success', 'Rubro eliminado correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('rubros.index')
                ->with('error', 'Error al eliminar el rubro: ' . $e->getMessage());
        }
    }
}
