<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            
            // Data Pribadi
            $table->string('nisn', 10)->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('warga_negara', ['WNI', 'WNA'])->default('WNI');
            $table->decimal('nilai_ujian_nasional', 5, 2)->nullable();
            
            // Kontak & Alamat
            $table->text('alamat');
            $table->string('email')->unique();
            $table->string('nomor_hp', 15);
            $table->string('asal_tk');
            
            // Data Orang Tua
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->bigInteger('penghasilan_ortu')->nullable();
            
            // Data Akun
            $table->string('username')->unique();
            $table->string('password');
            $table->string('foto')->nullable();
            
            // Status Pendaftaran
            $table->enum('status_pendaftaran', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_pendaftaran')->useCurrent();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index(['status_pendaftaran', 'tanggal_pendaftaran']);
            $table->index('email');
            $table->index('nisn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};