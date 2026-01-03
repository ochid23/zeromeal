<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    use HasFactory;
    
    // 1. Sesuaikan Nama Tabel
    protected $table = 'daftar_belanja'; 
    
    // 2. Sesuaikan Primary Key
    protected $primaryKey = 'belanja_id';

    public $timestamps = false; 

    // 3. Sesuaikan Kolom (Fillable)
    protected $fillable = [
        'user_id',
        'barang_id',     // Foreign Key
        'harga',         // Input Harga
        'jumlah_produk', // Input Jumlah (Angka)
        'status_beli'    // Checkbox (0/1)
    ];

    // 4. Relasi ke Tabel Barang (Untuk ambil nama barang)
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }
}