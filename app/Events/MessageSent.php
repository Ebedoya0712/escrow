<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * La instancia del mensaje que se transmitirá.
     *
     * @var \App\Models\Message
     */
    public $message;

    /**
     * Crea una nueva instancia del evento.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Obtiene los canales en los que el evento debería transmitirse.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Transmitir en un canal privado específico para esta transacción.
        // Solo los usuarios autorizados en routes/channels.php podrán escuchar.
        return [
            new PrivateChannel('chat.' . $this->message->transaction_id),
        ];
    }

    /**
     * El nombre con el que se transmitirá el evento.
     */
    public function broadcastAs()
    {
        // El JavaScript escuchará por '.new-message' en lugar del nombre de la clase.
        return 'new-message';
    }
}