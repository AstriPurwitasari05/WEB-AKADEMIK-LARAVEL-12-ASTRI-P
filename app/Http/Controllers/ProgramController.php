<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display the program page
     */
    public function index()
    {
        // Data untuk halaman program
        $programs = [
            [
                'name' => 'Program STEM',
                'description' => 'Program pembelajaran sains, teknologi, engineering dan matematika yang terintegrasi untuk mengembangkan kemampuan berpikir kritis siswa.',
                'status' => 'Aktif',
                'tags' => ['Sains', 'Teknologi', 'Matematika'],
                'icon' => 'fas fa-microscope'
            ],
            [
                'name' => 'Program Bilingual',
                'description' => 'Program pembelajaran dua bahasa (Indonesia-Inggris) untuk meningkatkan kemampuan komunikasi global siswa sejak dini.',
                'status' => 'Aktif',
                'tags' => ['Bahasa', 'Komunikasi', 'Global'],
                'icon' => 'fas fa-language'
            ],
            [
                'name' => 'Program Seni & Kreativitas',
                'description' => 'Program pengembangan bakat seni dan kreativitas siswa melalui berbagai aktivitas seni musik, tari, dan lukis.',
                'status' => 'Aktif',
                'tags' => ['Seni', 'Kreativitas', 'Ekspresi'],
                'icon' => 'fas fa-palette'
            ],
            [
                'name' => 'Program Olahraga Prestasi',
                'description' => 'Program pembinaan olahraga untuk mengembangkan bakat dan prestasi siswa di berbagai cabang olahraga.',
                'status' => 'Aktif',
                'tags' => ['Olahraga', 'Prestasi', 'Kesehatan'],
                'icon' => 'fas fa-dumbbell'
            ],
            [
                'name' => 'Program Lingkungan Hijau',
                'description' => 'Program edukasi lingkungan dan praktik ramah lingkungan untuk membentuk karakter peduli lingkungan pada siswa.',
                'status' => 'Aktif',
                'tags' => ['Lingkungan', 'Konservasi', 'Karakter'],
                'icon' => 'fas fa-leaf'
            ],
            [
                'name' => 'Program Digital Literacy',
                'description' => 'Program pembelajaran teknologi informasi dan komunikasi untuk mempersiapkan siswa menghadapi era digital.',
                'status' => 'Aktif',
                'tags' => ['Teknologi', 'Digital', 'Coding'],
                'icon' => 'fas fa-laptop-code'
            ]
        ];

        $statistics = [
            'program_count' => 8,
            'mata_pelajaran' => 15,
            'guru_profesional' => 25,
            'siswa_aktif' => 450
        ];

        return view('program', compact('programs', 'statistics'));
    }

    /**
     * Display the statistics page
     */
    public function statistik()
    {
        $data = [
            'total_siswa' => 450,
            'total_guru' => 25,
            'total_program' => 8,
            'tingkat_kelulusan' => 98.5,
            'siswa_per_kelas' => [
                'Kelas I' => 75,
                'Kelas II' => 78,
                'Kelas III' => 72,
                'Kelas IV' => 70,
                'Kelas V' => 80,
                'Kelas VI' => 75
            ]
        ];

        return view('statistik', compact('data'));
    }
}