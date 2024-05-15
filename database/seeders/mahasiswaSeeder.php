<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\mahasiswa;

class mahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Nanda', 'kelas' => 'XI PPLG 3', 'role_status' => 'mahasiswa', 'email' => 'nanda@gmail.com', 'password' => Hash::make('sedapmantap123')]
        ];

        foreach ($data as $val){
            mahasiswa::insert([
                'nama' => $val['name'],
                'kelas' => $val['kelas'],
                'role_status' => $val['role_status'],
                'email' => $val['email'],
                'password' => $val['password'],
            ]);
        }
    }
}
