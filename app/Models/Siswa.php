<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        // Data Pribadi
        'nisn',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'warga_negara',
        'nilai_ujian_nasional',
        // Kontak & Alamat
        'alamat',
        'email',
        'nomor_hp',
        'asal_tk',
        // Data Orang Tua
        'nama_ayah',
        'nama_ibu',
        'penghasilan_ortu',
        // Data Akun
        'username',
        'password',
        'foto',
        // Status Pendaftaran
        'status_pendaftaran',
        'keterangan',
    ];

    protected $dates = [
        'tanggal_lahir',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'penghasilan_ortu' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    // PERBAIKAN: Accessor for photo URL
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            // Cek apakah file ada di storage
            if (Storage::disk('public')->exists($this->foto)) {
                return Storage::disk('public')->url($this->foto);
            }
            // Jika path tidak ada di storage, coba akses melalui route
            return route('siswa.photo', ['id' => $this->id]);
        }
        
        // Return default avatar jika tidak ada foto
        return asset('images/default-avatar.png');
    }

    // TAMBAHAN: Accessor untuk cek apakah foto ada
    public function getHasFotoAttribute()
    {
        return !empty($this->foto) && Storage::disk('public')->exists($this->foto);
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            'pending' => 'badge-warning',
            'diterima' => 'badge-success',
            'ditolak' => 'badge-danger',
        ];
        return $statusClasses[$this->status_pendaftaran] ?? 'badge-secondary';
    }

    // Accessor untuk status icon
    public function getStatusIconAttribute()
    {
        $statusIcons = [
            'pending' => 'fas fa-clock',
            'diterima' => 'fas fa-check-circle',
            'ditolak' => 'fas fa-times-circle',
        ];
        return $statusIcons[$this->status_pendaftaran] ?? 'fas fa-question-circle';
    }

    // Accessor untuk format tanggal lahir
    public function getTanggalLahirFormattedAttribute()
    {
        return $this->tanggal_lahir->format('d F Y');
    }

    // Accessor untuk umur
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir->age;
    }

    // Accessor untuk penghasilan orang tua formatted
    public function getPenghasilanFormatAttribute()
    {
        if ($this->penghasilan_ortu) {
            return 'Rp ' . number_format($this->penghasilan_ortu, 0, ',', '.');
        }
        return 'Tidak diisi';
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_pendaftaran', $status);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
        });
    }

    // Scope untuk siswa aktif (diterima)
    public function scopeActive($query)
    {
        return $query->where('status_pendaftaran', 'diterima');
    }

    // Scope untuk siswa pending
    public function scopePending($query)
    {
        return $query->where('status_pendaftaran', 'pending');
    }

    // Boot method for model events
    protected static function boot()
    {
        parent::boot();

        // Delete photo when model is deleted
        static::deleting(function ($siswa) {
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
        });
    }

    // Method untuk cek apakah siswa diterima
    public function isDiterima()
    {
        return $this->status_pendaftaran === 'diterima';
    }

    // Method untuk cek apakah siswa pending
    public function isPending()
    {
        return $this->status_pendaftaran === 'pending';
    }

    // Method untuk cek apakah siswa ditolak
    public function isDitolak()
    {
        return $this->status_pendaftaran === 'ditolak';
    }
}