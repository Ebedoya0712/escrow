<?php

namespace App\Events;

    use App\Models\Transaction;
    use Illuminate\Broadcasting\PrivateChannel;
    use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
    use Illuminate\Foundation\Events\Dispatchable;
    use Illuminate\Queue\SerializesModels;

    class TransactionStatusUpdated implements ShouldBroadcast
    {
        use Dispatchable, SerializesModels;

        public $transaction;

        public function __construct(Transaction $transaction)
        {
            $this->transaction = $transaction;
        }

        public function broadcastOn(): array
        {
            return [new PrivateChannel('chat.' . $this->transaction->id)];
        }

        public function broadcastAs()
        {
            return 'status-updated';
        }
    }
