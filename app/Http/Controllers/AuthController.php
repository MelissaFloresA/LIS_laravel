<?php

namespace App\Http\Controllers;

use App\Models\EmpleadosModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {

            if (!session('ID_Empresa') && !session('ID_Rol')) {
                return view('login');
            } else {
                if (session('ID_Empresa') == 'CUPON') {
                    return redirect()->route('empresas.index');
                } else if (session('ID_Rol') == 1) {
                    return redirect()->route('cupones.index');
                } else {
                    return redirect()->route('cupones.canjear');
                }
            }
        } elseif ($request->isMethod('post')) {
            $validated = $request->validate([
                'Correo' => 'required|string|email',
                'Contrasena' => 'required|string',
            ]);

            $empleado = EmpleadosModel::where('Correo', $validated['Correo'])->first();

            if (!$empleado || !Hash::check($validated['Contrasena'], $empleado->Contrasena)) {
                return redirect()->route('auth.login')->with('error', 'Correo o contraseña incorrectos')->withInput();
            } else {
                session([
                    'ID_Empleado' => $empleado->ID_Empleado,
                    'ID_Empresa' => $empleado->ID_Empresa,
                    'ID_Rol' => $empleado->ID_Rol,
                ]);

                if ($empleado->ID_Empresa == 'CUPON') {
                    return redirect()->route('empresas.index');
                } else if ($empleado->ID_Rol == 1) {
                    return redirect()->route('cupones.index');
                } else {
                    return redirect()->route('cupones.canjear');
                }
            }
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('auth.login');
    }
}
