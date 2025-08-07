<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('user_payment_methods', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('method_name'); // Ej: "PayPal", "AirTM", "Banco Nacional"
                $table->string('label'); // Ej: "Mi cuenta personal", "Cuenta de la empresa"
                $table->text('details'); // Aquí se guardan los datos (correo, n° de cuenta, etc.)
                $table->boolean('is_default')->default(false); // Para marcar un método como preferido
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('user_payment_methods');
        }
    };