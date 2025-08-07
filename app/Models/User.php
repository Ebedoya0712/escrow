<?php
// ARCHIVO: app/Models/User.php
// Este modelo ya existe, solo necesitas actualizarlo con las relaciones y el campo 'role'.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Añadir 'role' para que se pueda asignar masivamente.
        'locale',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación: Un usuario participa en muchas transacciones.
     * Se usa una tabla pivote (transaction_user) para definir esta relación.
     */
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)->withPivot('role')->withTimestamps();
    }

    /**
     * Relación: Un usuario puede enviar muchos mensajes.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function paymentMethods()
        {
            return $this->hasMany(UserPaymentMethod::class);
        }
}