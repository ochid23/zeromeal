<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisBarang extends Model
{
    use HasFactory;
    protected $table = 'inventaris_barang';
    protected $primaryKey = 'inventaris_id';
    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'barang_id',
        'jumlah',
        'lokasi_penyimpanan',
        'tanggal_pembelian',
        'tanggal_kadaluarsa', // <--- WAJIB DITAMBAHKAN AGAR BISA DI-EDIT MANUAL
    ];
}