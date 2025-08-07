<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $querySource = null;

        // 1. Decidir si buscar en las transacciones del usuario o en todas
        if ($user->role === 'admin') {
            // Si es admin, usamos el modelo Transaction directamente para buscar en toda la tabla
            $querySource = Transaction::query();
        } else {
            // Si es un usuario normal, usamos la relación para buscar solo en sus transacciones
            $querySource = $user->transactions();
        }

        // 2. Realizar las consultas usando la fuente correcta
        $activeTransactions = (clone $querySource)->whereIn('status', ['pending', 'accepted', 'payment_verified', 'item_delivered'])->count();
        $completedTransactions = (clone $querySource)->where('status', 'completed')->count();
        $disputedTransactions = (clone $querySource)->where('status', 'disputed')->count(); // Corregido 'dispute' a 'disputed'
        $cancelledTransactions = (clone $querySource)->where('status', 'cancelled')->count();
        
        // 3. Obtener las transacciones recientes
        $recentTransactions = (clone $querySource)->latest()->take(5)->get();

        return view('panel.dashboard', compact(
            'activeTransactions',
            'completedTransactions',
            'disputedTransactions',
            'cancelledTransactions',
            'recentTransactions'
        ));
    }
}