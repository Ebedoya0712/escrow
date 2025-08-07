<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra la vista del formulario de registro.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesa el formulario de registro.
     */
    public function storeRegistration(Request $request)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // 2. Crear el nuevo usuario
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Redirigir a la página de login con un mensaje de éxito
        return redirect()->route('login')->with('status', '¡Tu cuenta ha sido creada exitosamente!');
    }

    /**
     * Muestra la vista del formulario de inicio de sesión.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el formulario de inicio de sesión.
     */
    public function storeLogin(Request $request)
    {
        // 1. Validar los datos
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Intentar autenticar al usuario
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // 3. Redirigir al dashboard si la autenticación es exitosa
            return redirect()->intended(route('dashboard')); // Redirige a la última página intentada o al dashboard
        }

        // 4. Si falla, redirigir de vuelta con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // Redirigir a la página de inicio
    }
}