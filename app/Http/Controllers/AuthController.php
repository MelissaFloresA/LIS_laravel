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

            $cliente = EmpleadosModel::where('Correo', $validated['Correo'])->first();

            if (!$cliente || !Hash::check($validated['Contrasena'], $cliente->Contrasena)) {
                return redirect()->route('auth.login')->with('error', 'Correo o contraseña incorrectos')->withInput();
            } else {
                session([
                    'ID_Empresa' => $cliente->ID_Empresa,
                    'ID_Rol' => $cliente->ID_Rol,
                ]);

                if ($cliente->ID_Empresa == 'CUPON') {
                    return redirect()->route('empresas.index');
                } else if ($cliente->ID_Rol == 1) {
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
