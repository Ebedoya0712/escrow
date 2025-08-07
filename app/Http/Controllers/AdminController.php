<?php

namespace App\Http\Controllers;

use App\Events\AdminJoinedChat;
use App\Events\TransactionStatusUpdated;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Muestra una lista de todas las transacciones del sistema.
     */
    public function index()
    {
        $transactions = Transaction::with('participants')->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Muestra los detalles de una transacción específica desde la perspectiva del admin.
     */
    public function show(Transaction $transaction)
    {
        // SIMPLIFICACIÓN: Al igual que en el otro controlador, ya no es necesario
        // cargar los mensajes aquí. La relación en el modelo se encarga del orden.
        $transaction->load('participants');

        $seller = $transaction->participants()->wherePivot('role', 'seller')->first();
        if ($seller) {
            $seller->load('paymentMethods');
        }

        return view('admin.transactions.show', compact('transaction', 'seller'));
    }

    /**
     * Actualiza el estado de una transacción.
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,payment_verified,item_delivered,completed,cancelled,disputed',
        ]);

        $transaction->update(['status' => $validated['status']]);

        broadcast(new \App\Events\TransactionStatusUpdated($transaction->fresh()))->toOthers();

        if ($validated['status'] === 'completed') {
            $seller = $transaction->participants()->wherePivot('role', 'seller')->first();
            
            if ($seller && $seller->email) {
                // Guardamos el idioma actual y lo cambiamos al del vendedor
                $currentLocale = App::getLocale();
                App::setLocale($seller->locale ?? 'en');

                // Enviamos el correo (ahora se generará en el idioma del vendedor)
                Mail::to($seller->email)->send(new PaymentSent($transaction));

                // Restauramos el idioma original
                App::setLocale($currentLocale);
            }
        }

        return back()->with('success', '¡El estado de la transacción ha sido actualizado!');
    }

    /**
     * Marca que un admin se ha unido al chat y notifica a los participantes.
     */
    public function joinChat(Transaction $transaction)
    {
        // --- CORRECCIÓN ---
        // Se ha flexibilizado la validación. El admin puede unirse a cualquier
        // transacción activa (no pendiente, ni cancelada, ni completada)
        // siempre y cuando no se haya unido antes.
        $allowedToJoinStatuses = ['accepted', 'payment_verified', 'item_delivered', 'disputed'];

        if (!in_array($transaction->status, $allowedToJoinStatuses) || $transaction->admin_joined_at) {
            return response()->json(['error' => 'No es posible unirse al chat en este estado de la transacción.'], 403);
        }

        // Actualizar la transacción
        $transaction->update(['admin_joined_at' => now()]);

        // Añadir al admin como participante formal de la transacción
        $transaction->participants()->attach(Auth::id(), ['role' => 'admin']);

        // Crear un mensaje de sistema
        $systemMessage = $transaction->messages()->create([
            'user_id' => Auth::id(),
            'content' => __('messages.system_messages.admin_joined_chat'),
        ]);
        
        $systemMessage->load('user');

        // Transmitir el evento de que el admin se unió
        broadcast(new AdminJoinedChat($transaction))->toOthers();
        
        // Transmitir el mensaje del sistema para que aparezca en el chat
        broadcast(new \App\Events\MessageSent($systemMessage))->toOthers();

        return response()->json(['success' => true, 'message' => 'Te has unido al chat.']);
    }
}
