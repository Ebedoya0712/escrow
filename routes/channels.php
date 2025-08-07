<?php

use App\Models\Transaction;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Este es el canal que autoriza el acceso al chat
Broadcast::channel('chat.{transactionId}', function ($user, $transactionId) {
    // --- CORRECCIÓN ---
    // Primero, se verifica si el usuario es un administrador.
    if ($user->role === 'admin') {
        return true; // Si es admin, siempre tiene permiso para escuchar el canal.
    }

    // Si no es un administrador, se aplica la lógica original para los usuarios normales.
    $transaction = Transaction::find($transactionId);

    // Se verifica si la transacción existe y si el usuario actual es un participante.
    return $transaction && $transaction->participants->contains($user);
});
