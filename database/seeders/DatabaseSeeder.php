<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'id' => intval((microtime(true) * 10000)),
            'name' => 'Desa Kwasen',
            'username' => 'kwasen',
            'password' => bcrypt('123'),
        ]);

        // membuat kriteria
        Kriteria::create([
            'id' => intval((microtime(true) * 10000)),
            'penghasilan' => 500000,
            'status' => "Kosong",
            'polri_asn' => 'Ya',
            'pbl' => 'Penerima',
            'dtks' => 'Belum',
        ]);

        // membuat periode
        Periode::create([
            'periode' => "April - Mei 2024",
            'tanggal_mulai' => "2024-04-01",
            'tanggal_akhir' => "2024-05-31",
            'maksimal_penerima' => 250
        ]);
    }
}
