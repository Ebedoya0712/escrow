<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'content',
        'role'
    ];

    /**
     * Relación: Un mensaje pertenece a una única transacción.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relación: Un mensaje fue enviado por un único usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}