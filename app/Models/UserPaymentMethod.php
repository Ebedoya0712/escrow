<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class UserPaymentMethod extends Model
    {
        use HasFactory;

        protected $fillable = [
            'user_id',
            'method_name',
            'label',
            'details',
            'is_default',
        ];

        /**
         * Relación: Un método de pago pertenece a un usuario.
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }