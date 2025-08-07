<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'amount',
        'status',
        'creator_id',
        'cancellation_reason',
        'admin_joined_at',
    ];

    /**
     * Boot a new instance of the model.
     * Automáticamente genera un UUID al crear una nueva transacción.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            if (empty($transaction->uuid)) {
                $transaction->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Relación: Una transacción tiene muchos participantes (usuarios).
     */
    public function participants()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    /**
     * Relación: Una transacción tiene muchos mensajes en su chat.
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->oldest();
    }

    /**
     * Relación: Una transacción fue creada por un usuario específico.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}