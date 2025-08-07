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

        // --- CORRECCIÓN DEFINITIVA PARA EL ERROR 403 ---
        // Se verifica si el usuario es admin O si es un participante de la transacción.
        if ($user->role !== 'admin' && !$transaction->participants->contains($user)) {
            abort(403, 'No tienes permiso para enviar mensajes en esta transacción.');
        }

        // 2. Validar los datos del formulario
        $request->validate([
            'content' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $content = $request->input('content');

        // 3. Si hay un archivo adjunto, guardarlo y crear un enlace
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments/' . $transaction->id, 'public');
            $fileName = $request->file('attachment')->getClientOriginalName();
            $fileUrl = asset('storage/' . $path);
            
            $linkClass = $user->id == $transaction->creator_id ? 'text-white' : 'text-dark';
            $attachmentLink = "<a href='{$fileUrl}' target='_blank' class='{$linkClass} text-decoration-underline'>{$fileName}</a>";
            $content = $content ? $content . "\nArchivo adjunto: " . $attachmentLink : "Archivo adjunto: " . $attachmentLink;
        }
        
        // 4. Asegurarse de que el mensaje no esté vacío
        if(!$content) {
            return response()->json(['error' => 'El mensaje no puede estar vacío.'], 422);
        }

        // 5. Crear el mensaje en la base de datos
        $message = $transaction->messages()->create([
            'user_id' => $user->id,
            'content' => $content,
        ]);
        
        $message->load('user');

        // 6. Transmitir el evento a los demás participantes del chat
        broadcast(new MessageSent($message))->toOthers();

        // 7. Devolver el mensaje como JSON para que el remitente lo vea al instante
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