<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang'; // Nama tabel di SQL
    protected $primaryKey = 'barang_id'; // Primary Key Custom
    public $timestamps = false; // Matikan timestamps default
}