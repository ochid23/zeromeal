<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Use the API database connection
    // protected $connection = 'mysql_api'; // Removed to use default connection from config

    // PENTING: Menyesuaikan dengan Primary Key di database SQL Anda
    protected $primaryKey = 'user_id';

    // PENTING: Matikan timestamps otomatis karena tabel users tidak punya created_at/updated_at
    public $timestamps = false; 

    protected $fillable = [
        'nama',        // Sesuai kolom database (bukan 'name')
        'email',
        'password',
        'no_telepon',  // Sesuai kolom database
        'budget',
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
            'budget' => 'decimal:2',
        ];
    }
}