<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Guarda un nuevo mensaje y devuelve una respuesta JSON para el chat dinámico.
     */
    public function store(Request $request, Transaction $transaction)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && !$transaction->participants->contains($user)) {
            abort(403, 'No tienes permiso para enviar mensajes en esta transacción.');
        }

        $request->validate([
            'content' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $content = $request->input('content');

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments/' . $transaction->id, 'public');
            $fileName = $request->file('attachment')->getClientOriginalName();
            $fileUrl = asset('storage/' . $path);
            
            $linkClass = $user->id == $transaction->creator_id ? 'text-white' : 'text-dark';
            $attachmentLink = "<a href='{$fileUrl}' target='_blank' class='{$linkClass} text-decoration-underline'>{$fileName}</a>";

            // --- CORRECCIÓN ---
            // Ahora se usa la clave de traducción
            $attachmentText = __('messages.chat.attachment');
            $content = $content ? $content . "\n" . $attachmentText . ": " . $attachmentLink : $attachmentText . ": " . $attachmentLink;
        }
        
        if(!$content) {
            return response()->json(['error' => 'El mensaje no puede estar vacío.'], 422);
        }

        $message = $transaction->messages()->create([
            'user_id' => $user->id,
            'content' => $content,
        ]);
        
        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    /**
     * Obtiene los mensajes nuevos desde un ID específico (para el polling).
     */
    public function fetch(Request $request, Transaction $transaction)
    {
        // 1. Validar que el usuario actual participa en la transacción
        if (!$transaction->participants->contains(Auth::user())) {
            abort(403);
        }

        // 2. Obtener el ID del último mensaje que el cliente ya tiene
        $lastMessageId = $request->query('last_message_id', 0);

        // 3. Buscar en la base de datos todos los mensajes con un ID mayor
        $messages = $transaction->messages()
                                ->where('id', '>', $lastMessageId)
                                ->with('user') // Cargar la información del usuario que envió el mensaje
                                ->oldest() // Obtenerlos en orden cronológico
                                ->get();

        // 4. Devolver los mensajes nuevos como JSON
        return response()->json($messages);
    }
}