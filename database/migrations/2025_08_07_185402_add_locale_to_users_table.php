<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('users', function (Blueprint $table) {
                // Añadimos una columna para guardar el idioma (ej: 'es', 'en')
                $table->string('locale', 2)->default('en')->after('email');
            });
        }

        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('locale');
            });
        }
    };
    