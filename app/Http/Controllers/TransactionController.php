<?php

namespace App\Http\Controllers;

use App\Events\TransactionCancelled;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    $transactions = $user->transactions()
                        ->with(['participants' => function($query) use ($user) {
                            $query->where('user_id', $user->id);
                        }])
                        ->where('status', '!=', 'cancelled')
                        ->latest()
                        ->get();

    // Formatear datos para DataTables si es solicitud AJAX
    if(request()->ajax()) {
        return datatables()->of($transactions)
            ->addColumn('actions', function($transaction) {
                return view('partials.transaction_actions', compact('transaction'))->render();
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    return view('panel.transactions.index', compact('transactions'));
}

    

    public function create()
    {
        return view('panel.transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'role' => 'required|in:buyer,seller',
        ]);

        $transaction = Transaction::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'creator_id' => Auth::id(),
            'status' => 'pending',
        ]);

        $transaction->participants()->attach(Auth::id(), ['role' => $validated['role']]);

        // --- CORRECCIÓN ---
        // Se ha cambiado 'success' por 'sweet_success' para que coincida
        // con la lógica del layout y la del método 'update'.
        return redirect()->route('transacciones.index')->with('sweet_success', [
            'title' => __('messages.alerts.transaction_created_title'),
            'text' => __('messages.alerts.transaction_created_text'),
        ]);
    }

    public function show(Transaction $transaction)
    {
        if (!Auth::user()->transactions->contains($transaction)) {
            abort(403, 'No tienes permiso para ver esta transacción.');
        }

        $transaction->load('participants');
        
        // --- CORRECCIÓN ---
        // 1. Encontrar al usuario administrador.
        $admin = User::where('role', 'admin')->first();

        // 2. Obtener los métodos de pago de ese administrador.
        // Si no se encuentra un admin, se devuelve una colección vacía para evitar errores.
        $platformPaymentMethods = $admin ? $admin->paymentMethods : collect();

        // 3. Definir los pasos del proceso para la línea de tiempo
        $processSteps = [
            'accepted' => 'messages.transaction_details.step_accepted',
            'payment_verified' => 'messages.transaction_details.step_payment_verified',
            'item_delivered' => 'messages.transaction_details.step_item_delivered',
            'completed' => 'messages.transaction_details.step_payment_released',
        ];

        // 4. Determinar el índice del paso actual
        $statusKeys = array_keys($processSteps);
        $currentStepIndex = array_search($transaction->status, $statusKeys);

        $invitationUrl = route('transactions.invitation', ['transaction' => $transaction->uuid]);

        // 5. Enviar todas las variables a la vista
        return view('panel.transactions.show', compact(
            'transaction', 
            'invitationUrl', 
            'platformPaymentMethods',
            'processSteps',
            'currentStepIndex'
        ));
    }

    /**
     * Muestra la página de invitación a una transacción.
     */
    public function showInvitation(Transaction $transaction)
    {
        // Determinar el rol del creador para asignar el opuesto al invitado
        $creator = $transaction->creator;
        $creatorRole = $transaction->participants()->where('user_id', $creator->id)->first()->pivot->role;
        
        // --- CORRECCIÓN ---
        // Ahora se usan las claves de traducción para determinar el rol del invitado.
        $inviteeRole = ($creatorRole == 'seller') 
            ? __('messages.transactions_list.role_buyer') 
            : __('messages.transactions_list.role_seller');

        return view('panel.transactions.invitation', compact('transaction', 'creator', 'inviteeRole'));
    }

    /**
     * Procesa la aceptación de una invitación.
     */
    public function acceptInvitation(Request $request, Transaction $transaction)
    {
        $user = Auth::user();

        // Validaciones de seguridad
        if ($transaction->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Esta transacción ya no está pendiente de aceptación.');
        }
        if ($transaction->participants->contains($user)) {
            return redirect()->route('transacciones.show', $transaction)->with('error', 'Ya eres parte de esta transacción.');
        }

        // Determinar el rol del invitado
        $creatorRole = $transaction->participants()->first()->pivot->role;
        $inviteeRole = ($creatorRole == 'seller') ? 'buyer' : 'seller';

        // Añadir al nuevo participante y actualizar el estado
        $transaction->participants()->attach($user->id, ['role' => $inviteeRole]);
        $transaction->status = 'accepted';
        $transaction->save();

        return redirect()->route('transacciones.show', $transaction)->with('success', '¡Te has unido a la transacción exitosamente!');
    }

    /**
     * Muestra el formulario para editar una transacción.
     */
    public function edit(Transaction $transaction)
    {
        if (!Auth::user()->transactions->contains($transaction)) {
            abort(403, 'Acción no autorizada.');
        }
        return view('panel.transactions.edit', compact('transaction'));
    }

    /**
     * Actualiza una transacción en la base de datos.
     */
    public function update(Request $request, Transaction $transaction)
    {
        if (!Auth::user()->transactions->contains($transaction)) {
            abort(403, 'Acción no autorizada.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $transaction->update($validated);

        // --- CORRECCIÓN ---
        // Ahora enviamos un array con los textos traducidos para SweetAlert
        return redirect()->route('transacciones.index')->with('sweet_success', [
            'title' => __('messages.alerts.transaction_updated_title'),
            'text' => __('messages.alerts.transaction_updated_text'),
        ]);
    }

    /**
     * Actualiza una transacción en la base de datos.
     */
    public function cancel(Request $request, Transaction $transaction)
    {
        if (!Auth::user()->transactions->contains($transaction)) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate(['reason' => 'required|string|max:1000']);

        $transaction->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason,
        ]);

        // Esta línea es correcta y ahora usará el evento ligero que acabamos de verificar.
        broadcast(new TransactionCancelled($transaction, Auth::user()))->toOthers();

        return redirect()->route('transacciones.index')->with('sweet_success', [
            'title' => __('messages.alerts.cancelled_title'),
            'text' => __('messages.alerts.cancelled_text'),
        ]);
    }


    /**
     * Elimina una transacción.
     */
    
}