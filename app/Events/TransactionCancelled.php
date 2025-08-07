<?php

namespace App\Events;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCancelled implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * El ID de la transacción que fue cancelada.
     * @var int
     */
    public $transactionId;

    /**
     * El usuario que canceló la transacción (solo con los datos necesarios).
     * @var array
     */
    public $cancellingUser;

    /**
     * Crea una nueva instancia del evento.
     */
    public function __construct(Transaction $transaction, User $cancellingUser)
    {
        // Solo guardamos los datos esenciales para mantener el "paquete" ligero.
        $this->transactionId = $transaction->id;
        $this->cancellingUser = [
            'id' => $cancellingUser->id,
            'name' => $cancellingUser->name,
        ];
    }

    /**
     * Obtiene los canales en los que el evento debería transmitirse.
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->transactionId)];
    }

    /**
     * El nombre con el que se transmitirá el evento.
     */
    public function broadcastAs()
    {
        return 'transaction-cancelled';
    }
}