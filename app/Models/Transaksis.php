<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksis extends Model
{
    protected $table = 'transaksis'; // Sesuaikan dengan nama tabel yang digunakan

    protected $fillable = [
        'mahasiswa_id',
        'nama_mahasiswa',
        'kelas_mahasiswa',
        'buku_id',
        'judul_buku',
        'mahasiswa_email',
        'returned_at'
    ];

    protected $dates = ['returned_at'];
}
