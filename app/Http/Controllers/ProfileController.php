<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserPaymentMethod;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Muestra la página principal del perfil del usuario.
     */
    public function show()
    {
        $user = Auth::user();
        $paymentMethods = $user->paymentMethods;
        return view('panel.profile.show', compact('user', 'paymentMethods'));
    }

    /**
     * Actualiza los datos del perfil del usuario (nombre y email).
     */
    public function updateDetails(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', '¡Tu perfil ha sido actualizado exitosamente!');
    }

    /**
     * Actualiza la contraseña del usuario.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', '¡Tu contraseña ha sido cambiada exitosamente!');
    }

    // --- Métodos para Métodos de Pago (ya existentes) ---

    public function paymentMethods()
    {
        $user = Auth::user();
        // Para el usuario normal, obtenemos el primer (y único) método que tenga.
        $paymentMethod = $user->role === 'admin' ? null : $user->paymentMethods()->first();
        $paymentMethods = $user->paymentMethods; // Para la lista del admin

        return view('panel.profile.payment', compact('paymentMethods', 'paymentMethod'));
    }

    /**
     * Guarda o actualiza un método de pago según el rol del usuario.
     */
    public function storePaymentMethod(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'method_name' => 'required|string',
            'label' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        $data = $request->only(['method_name', 'label', 'details']);

        if ($user->role === 'admin') {
            // Los administradores pueden añadir múltiples métodos.
            $user->paymentMethods()->create($data);
            $message = '¡Método de pago añadido exitosamente!';
        } else {
            // Los usuarios normales solo pueden tener uno.
            // updateOrCreate buscará un método para este usuario y lo actualizará,
            // o creará uno nuevo si no existe.
            $user->paymentMethods()->updateOrCreate(
                ['user_id' => $user->id], // Condición de búsqueda
                $data                    // Datos para actualizar o crear
            );
            $message = '¡Tu método de pago ha sido actualizado exitosamente!';
        }

        return back()->with('success', $message);
    }

    /**
     * Elimina un método de pago.
     */
    public function destroyPaymentMethod(UserPaymentMethod $method)
    {
        if ($method->user_id !== Auth::id()) {
            abort(403);
        }

        $method->delete();

        return back()->with('success', 'Método de pago eliminado.');
    }
}