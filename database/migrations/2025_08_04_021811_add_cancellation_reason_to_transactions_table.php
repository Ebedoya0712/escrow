<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('transactions', function (Blueprint $table) {
                // Añadimos un campo de texto que puede ser nulo, después de la columna 'status'
                $table->text('cancellation_reason')->nullable()->after('status');
            });
        }

        public function down(): void
        {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('cancellation_reason');
            });
        }
    };