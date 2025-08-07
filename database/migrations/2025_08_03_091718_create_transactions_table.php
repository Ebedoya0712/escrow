<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Para el enlace único y seguro de la transacción
            $table->string('title');
            $table->text('description');
            $table->decimal('amount', 10, 2); // Guarda el monto con 2 decimales
            $table->enum('status', [
                'pending',          // Esperando que la otra parte acepte
                'accepted',         // Ambas partes han aceptado, esperando pago
                'payment_verified', // Admin verificó el pago
                'item_delivered',   // Vendedor marcó como entregado
                'completed',        // Comprador liberó el pago, transacción finalizada
                'cancelled',        // Transacción cancelada
                'disputed'          // En disputa, requiere intervención del admin
            ])->default('pending');
            $table->foreignId('creator_id')->constrained('users'); // Quién creó la transacción
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
