<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class RegisterController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function register()
    {
        return view('auth.registrar'); // Asegúrate de tener una vista para el registro
    }

    public function store(Request $request)
    {
        // Reglas de validación
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8', // Mínimo 8 caracteres
                'confirmed', // Asegura que coincida con el campo password_confirmation
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
                // La expresión regular asegura que la contraseña tenga al menos:
                // - Una letra minúscula
                // - Una letra mayúscula
                // - Un número
                // - Un carácter especial
            ],
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string|max:255',
        ];

        // Mensajes de error personalizados
        $messages = [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El correo es requerido',
            'email.unique' => 'El correo ya existe',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un carácter especial',
            'telefono.required' => 'El teléfono es requerido',
            'direccion.required' => 'La dirección es requerida',
        ];

        // Validar la solicitud
        $this->validate($request, $rules, $messages);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Encriptar la contraseña
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'es_cliente' => true, // Asignar el rol de cliente
        ]);

        // Autenticar al usuario después del registro
        auth()->login($user);

        // Redirigir al inicio
        return redirect()->route('inicio')->with('success', '¡Registro exitoso!');
    }
}