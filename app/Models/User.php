<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',   // <-- kembalikan ini
        'email',
        'phone',
        'role',
        'address',    // jika ada kolom ini
        'position',   // biarkan jika memang ada di tabel dan nullable
        'picture',    // biarkan jika memang ada di tabel dan nullable
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // helper role
    public function hasRole($roles)
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles, true);
    }
}
