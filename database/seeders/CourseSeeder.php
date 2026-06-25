<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder sederhana agar kursus langsung ada untuk test Postman.
        // Pastikan user test@example.com dibuat oleh DatabaseSeeder.
        $user = User::where('email', 'test@example.com')->first();
        if (! $user) {
            return;
        }

        Course::firstOrCreate(
            ['user_id' => $user->id, 'kode_mk' => 'IF401'],
            [
                'nama_mk' => 'Pemrograman Lanjut',
                'dosen' => 'Dosen Demo',
                'semester' => 1,
            ]
        );
    }
}

