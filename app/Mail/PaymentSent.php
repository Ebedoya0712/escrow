<?php

    namespace App\Mail;

    use App\Models\Transaction;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Mail\Mailable;
    use Illuminate\Mail\Mailables\Content;
    use Illuminate\Mail\Mailables\Envelope;
    use Illuminate\Queue\SerializesModels;

    class PaymentSent extends Mailable
    {
        use Queueable, SerializesModels;

        public $transaction;

        /**
         * Create a new message instance.
         */
        public function __construct(Transaction $transaction)
        {
            $this->transaction = $transaction;
        }

        /**
         * Get the message envelope.
         */
        public function envelope(): Envelope
        {
            return new Envelope(
                subject: __('messages.email.payment_sent_subject') . ' - ' . __('messages.table.transaction') . ' #' . $this->transaction->id,
            );
        }

        /**
         * Get the message content definition.
         */
        public function content(): Content
        {
            // Apuntamos a una nueva vista de Blade para el contenido del correo
            return new Content(
                markdown: 'emails.payment-sent',
            );
        }
    }
    