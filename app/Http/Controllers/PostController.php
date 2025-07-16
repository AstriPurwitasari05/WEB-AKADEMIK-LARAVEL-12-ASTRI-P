<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of siswa - PUBLIC ACCESS
     */
    public function index(Request $request)
    {
        $query = Siswa::query();

        // Search functionality
        if ($request->has("search") && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where("nama_lengkap", "like", "%" . $request->search . "%")
                  ->orWhere('nisn', 'like', "%" . $request->search . "%")
                  ->orWhere('email', 'like', "%" . $request->search . "%")
                  ->orWhere('username', 'like', "%" . $request->search . "%");
            });
        }

        // Filter by status
        if ($request->has("status") && $request->status) {
            $query->where("status_pendaftaran", $request->status);
        }

        $siswa = $query->latest()->paginate(10);

        // Pass authentication status to view
        $isAuthenticated = auth()->check();

        return view("siswa.index", compact("siswa", "isAuthenticated"));
    }

    /**
     * Show siswa details - PUBLIC ACCESS
     */
    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        $isAuthenticated = auth()->check();

        return view("siswa.show", compact("siswa", "isAuthenticated"));
    }

    /**
     * Show the form for creating a new siswa - PUBLIC ACCESS
     */
    public function create()
    {
        $isAuthenticated = auth()->check();

        return view("siswa.create", compact("isAuthenticated"));
    }

    /**
     * Store a newly created siswa - PUBLIC ACCESS
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data Pribadi
            "nisn" => "required|string|size:10|unique:siswa,nisn",
            "nama_lengkap" => "required|string|max:255",
            "tempat_lahir" => "required|string|max:255",
            "tanggal_lahir" => "required|date|before:today",
            "warga_negara" => "required|in:WNI,WNA",
            "nilai_ujian_nasional" => "nullable|numeric|min:0|max:100",
            // Kontak & Alamat
            "alamat" => "required|string",
            "email" => "required|email|unique:siswa,email",
            "nomor_hp" => "required|string|max:15",
            "asal_tk" => "required|string|max:255",
            // Data Orang Tua
            "nama_ayah" => "required|string|max:255",
            "nama_ibu" => "required|string|max:255",
            "penghasilan_ortu" => "nullable|integer|min:0",
            // Data Akun
            "username" => "required|string|max:255|unique:siswa,username",
            "password" => "required|string|min:8|confirmed",
            "foto" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            // Status Pendaftaran - hanya admin yang bisa set status
            "status_pendaftaran" => "nullable|in:pending,diterima,ditolak",
            "keterangan" => "nullable|string",
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Handle file upload
        if ($request->hasFile("foto")) {
            try {
                $validated["foto"] = $this->handleFileUpload($request->file("foto"));
                Log::info('Photo uploaded for new siswa', [
                    'name' => $validated['nama_lengkap'],
                    'foto_path' => $validated["foto"]
                ]);
            } catch (\Exception $e) {
                Log::error('Photo upload failed during store', [
                    'name' => $validated['nama_lengkap'],
                    'error' => $e->getMessage()
                ]);
                // Continue without photo if upload fails
                unset($validated["foto"]);
            }
        }

        // Set default status - selalu pending untuk pendaftaran public
        $validated['status_pendaftaran'] = 'pending';

        $siswa = Siswa::create($validated);

        Log::info('New siswa created', [
            'id' => $siswa->id,
            'name' => $siswa->nama_lengkap,
            'has_photo' => !empty($siswa->foto)
        ]);

        // Redirect berdasarkan authentication status
        if (auth()->check()) {
            return redirect()->route("siswa.index")->with("success", "Data siswa berhasil ditambahkan");
        } else {
            return redirect()->route("beranda")->with("success", "Pendaftaran berhasil! Data Anda akan diproses dan status akan diberitahukan melalui email.");
        }
    }

    /**
     * Advanced status change tracking for audit purposes
     */
    private function trackStatusChange($siswaId, $oldStatus, $newStatus, $reason = null, $isAutomatic = false)
    {
        try {
            // Log status change dengan detail lengkap
            Log::info('Status change tracked', [
                'siswa_id' => $siswaId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $reason,
                'is_automatic' => $isAutomatic,
                'changed_by' => auth()->check() ? auth()->user()->name : 'System/Public',
                'changed_at' => now()->toISOString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Optional: Save to audit table jika ada
            // StatusChangeLog::create([...]);
            
        } catch (\Exception $e) {
            Log::error('Failed to track status change', [
                'siswa_id' => $siswaId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if data changes require status reset
     */
    private function shouldResetStatusToPending($originalData, $newData)
    {
        // Fields yang mempengaruhi status
        $criticalFields = [
            'nisn',
            'nama_lengkap', 
            'tempat_lahir',
            'tanggal_lahir',
            'alamat',
            'email',
            'nomor_hp',
            'nilai_ujian_nasional',
            'nama_ayah',
            'nama_ibu',
            'penghasilan_ortu',
            'asal_tk'
        ];
        
        $hasChanges = false;
        $changedFields = [];
        
        foreach ($criticalFields as $field) {
            $oldValue = $originalData[$field] ?? null;
            $newValue = $newData[$field] ?? null;
            
            // Normalize values untuk comparison
            $oldValue = $this->normalizeValue($oldValue);
            $newValue = $this->normalizeValue($newValue);
            
            if ($oldValue !== $newValue) {
                $hasChanges = true;
                $changedFields[] = $field;
                
                Log::info('Critical field changed', [
                    'field' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue
                ]);
            }
        }
        
        return [
            'should_reset' => $hasChanges,
            'changed_fields' => $changedFields
        ];
    }

    /**
     * Normalize values for comparison
     */
    private function normalizeValue($value)
    {
        if (is_null($value)) {
            return '';
        }
        
        if (is_numeric($value)) {
            return (string)$value;
        }
        
        return trim((string)$value);
    }

    /**
     * Enhanced update method dengan detailed logging
     */
    public function updateWithAutoStatus(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $originalData = $siswa->toArray();
            $originalStatus = $siswa->status_pendaftaran;
            
            // Validation rules (same as before)
            $validationRules = [
                "nisn" => "required|string|size:10|unique:siswa,nisn," . $id,
                "nama_lengkap" => "required|string|max:255",
                "tempat_lahir" => "required|string|max:255",
                "tanggal_lahir" => "required|date|before:today",
                "warga_negara" => "required|in:WNI,WNA",
                "nilai_ujian_nasional" => "nullable|numeric|min:0|max:100",
                "alamat" => "required|string",
                "email" => "required|email|unique:siswa,email," . $id,
                "nomor_hp" => "required|string|max:15",
                "asal_tk" => "required|string|max:255",
                "nama_ayah" => "required|string|max:255",
                "nama_ibu" => "required|string|max:255",
                "penghasilan_ortu" => "nullable|integer|min:0",
                "username" => "required|string|max:255|unique:siswa,username," . $id,
                "password" => "nullable|string|min:8|confirmed",
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            // Admin can manually set status
            if (auth()->check()) {
                $validationRules["status_pendaftaran"] = "nullable|in:pending,diterima,ditolak";
                $validationRules["keterangan"] = "nullable|string";
            }

            $validated = $request->validate($validationRules);

            // Handle password
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Handle file upload
            if ($request->hasFile('foto')) {
                try {
                    if ($siswa->foto && Storage::disk("public")->exists($siswa->foto)) {
                        Storage::disk("public")->delete($siswa->foto);
                    }
                    $validated['foto'] = $this->handleFileUpload($request->file('foto'));
                } catch (\Exception $e) {
                    Log::error('Photo upload failed', ['error' => $e->getMessage()]);
                    unset($validated['foto']);
                }
            }

            $statusChangeInfo = null;

            // AUTO STATUS LOGIC - Enhanced version
            if (!auth()->check()) {
                // Check if critical data changed
                $resetCheck = $this->shouldResetStatusToPending($originalData, $validated);
                
                if ($resetCheck['should_reset'] && $originalStatus !== 'pending') {
                    $validated['status_pendaftaran'] = 'pending';
                    $validated['keterangan'] = 'Status otomatis diubah ke pending karena ada perubahan pada: ' . 
                                             implode(', ', $resetCheck['changed_fields']) . 
                                             ' pada ' . now()->format('d/m/Y H:i:s');
                    
                    $statusChangeInfo = [
                        'changed' => true,
                        'from' => $originalStatus,
                        'to' => 'pending',
                        'reason' => 'automatic_data_change',
                        'changed_fields' => $resetCheck['changed_fields']
                    ];
                    
                    // Track the automatic status change
                    $this->trackStatusChange(
                        $siswa->id, 
                        $originalStatus, 
                        'pending', 
                        'Data changed: ' . implode(', ', $resetCheck['changed_fields']),
                        true
                    );
                }
            } else {
                // Admin manual status change tracking
                if (isset($validated['status_pendaftaran']) && $validated['status_pendaftaran'] !== $originalStatus) {
                    $statusChangeInfo = [
                        'changed' => true,
                        'from' => $originalStatus,
                        'to' => $validated['status_pendaftaran'],
                        'reason' => 'manual_admin_change'
                    ];
                    
                    $this->trackStatusChange(
                        $siswa->id,
                        $originalStatus,
                        $validated['status_pendaftaran'],
                        $validated['keterangan'] ?? 'Manual admin change',
                        false
                    );
                }
            }

            // Update siswa
            $siswa->update($validated);

            // Prepare success message
            $successMessage = "Data siswa berhasil diperbarui";
            
            if ($statusChangeInfo && $statusChangeInfo['changed']) {
                if ($statusChangeInfo['reason'] === 'automatic_data_change') {
                    $successMessage .= ". Status otomatis diubah ke pending karena ada perubahan data penting.";
                } else {
                    $successMessage .= ". Status berhasil diubah dari " . 
                                     ucfirst($statusChangeInfo['from']) . " ke " . 
                                     ucfirst($statusChangeInfo['to']) . ".";
                }
            }

            // Log successful update
            Log::info('Siswa updated successfully', [
                'siswa_id' => $siswa->id,
                'updated_fields' => array_keys($validated),
                'status_change' => $statusChangeInfo,
                'updated_by' => auth()->check() ? auth()->user()->name : 'Public'
            ]);

            // Return response
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'data' => [
                        'id' => $siswa->id,
                        'status' => $siswa->status_pendaftaran,
                        'status_changed' => $statusChangeInfo ? $statusChangeInfo['changed'] : false
                    ]
                ]);
            }

            return redirect()->route("siswa.index")->with("success", $successMessage);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in update', [
                'siswa_id' => $id,
                'errors' => $e->errors()
            ]);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error in update with auto status', [
                'siswa_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Terjadi kesalahan saat mengupdate data';
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    /**
     * Show the form for editing - PUBLIC ACCESS
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $isAuthenticated = auth()->check();

        return view("siswa.edit", compact("siswa", "isAuthenticated"));
    }

    /**
     * Update siswa - PUBLIC ACCESS dengan pembatasan
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validationRules = [
            // Data Pribadi
            "nisn" => "required|string|size:10|unique:siswa,nisn," . $id,
            "nama_lengkap" => "required|string|max:255",
            "tempat_lahir" => "required|string|max:255",
            "tanggal_lahir" => "required|date|before:today",
            "warga_negara" => "required|in:WNI,WNA",
            "nilai_ujian_nasional" => "nullable|numeric|min:0|max:100",
            // Kontak & Alamat
            "alamat" => "required|string",
            "email" => "required|email|unique:siswa,email," . $id,
            "nomor_hp" => "required|string|max:15",
            "asal_tk" => "required|string|max:255",
            // Data Orang Tua
            "nama_ayah" => "required|string|max:255",
            "nama_ibu" => "required|string|max:255",
            "penghasilan_ortu" => "nullable|integer|min:0",
            // Data Akun
            "username" => "required|string|max:255|unique:siswa,username," . $id,
            "password" => "nullable|string|min:8|confirmed",
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Hanya admin yang bisa mengubah status
        if (auth()->check()) {
            $validationRules["status_pendaftaran"] = "required|in:pending,diterima,ditolak";
            $validationRules["keterangan"] = "nullable|string";
        }

        $validated = $request->validate($validationRules);

        // Hash password hanya jika diisi
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle file upload
        if ($request->hasFile('foto')) {
            try {
                // Delete old photo if exists
                if ($siswa->foto && Storage::disk("public")->exists($siswa->foto)) {
                    Storage::disk("public")->delete($siswa->foto);
                    Log::info('Old photo deleted', ['path' => $siswa->foto]);
                }

                $validated['foto'] = $this->handleFileUpload($request->file('foto'));
                Log::info('Photo updated for siswa', [
                    'id' => $siswa->id,
                    'name' => $siswa->nama_lengkap,
                    'new_foto_path' => $validated['foto']
                ]);
            } catch (\Exception $e) {
                Log::error('Photo upload failed during update', [
                    'siswa_id' => $siswa->id,
                    'error' => $e->getMessage()
                ]);
                // Don't update photo if upload fails
                unset($validated['foto']);
            }
        }

        // Hapus status dan keterangan jika bukan admin
        if (!auth()->check()) {
            unset($validated['status_pendaftaran']);
            unset($validated['keterangan']);
        }

        $siswa->update($validated);

        return redirect()->route("siswa.index")->with("success", "Data siswa berhasil diperbarui");
    }

    /**
     * Handle file upload - IMPROVED VERSION
     */
    private function handleFileUpload($file)
    {
        try {
            // Validate file
            if (!$file->isValid()) {
                throw new \Exception('File tidak valid');
            }

            // Create directory if not exists
            $uploadPath = 'siswa/foto';
            if (!Storage::disk('public')->exists($uploadPath)) {
                Storage::disk('public')->makeDirectory($uploadPath, 0755, true);
            }

            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . Str::random(10) . '.' . $extension;
            $fullPath = $uploadPath . '/' . $filename;

            // Store the file
            $stored = $file->storeAs($uploadPath, $filename, 'public');

            if (!$stored) {
                throw new \Exception('Gagal menyimpan file ke storage');
            }

            // Verify file was stored
            if (!Storage::disk('public')->exists($stored)) {
                throw new \Exception('File tidak tersimpan dengan benar');
            }

            Log::info('File uploaded successfully', [
                'original_name' => $originalName,
                'stored_path' => $stored,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            return $stored;

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file_name' => $file->getClientOriginalName() ?? 'unknown',
                'file_size' => $file->getSize() ?? 0
            ]);
            throw new \Exception('Gagal mengupload file: ' . $e->getMessage());
        }
    }

    /**
     * Display photo - PUBLIC ACCESS - FIXED VERSION
     */
    public function photo($id)
    {
        try {
            // Validate ID
            if (!is_numeric($id) || $id <= 0) {
                Log::warning('Invalid ID for photo request', ['id' => $id]);
                abort(400, 'ID tidak valid');
            }

            $siswa = Siswa::find($id);
            if (!$siswa) {
                Log::warning('Siswa not found for photo', ['id' => $id]);
                return $this->returnDefaultAvatar();
            }

            // Check if student has photo
            if (!$siswa->foto) {
                Log::info('No photo set for siswa', ['id' => $id]);
                return $this->returnDefaultAvatar();
            }

            // Check if file exists in storage
            if (!Storage::disk('public')->exists($siswa->foto)) {
                Log::warning('Photo file not found in storage', [
                    'id' => $id,
                    'foto_path' => $siswa->foto
                ]);
                return $this->returnDefaultAvatar();
            }

            // Get file path and info
            $filePath = Storage::disk('public')->path($siswa->foto);

            // Double check file exists physically
            if (!file_exists($filePath)) {
                Log::warning('Photo file does not exist physically', [
                    'id' => $id,
                    'file_path' => $filePath
                ]);
                return $this->returnDefaultAvatar();
            }

            // Get MIME type
            $mimeType = Storage::disk('public')->mimeType($siswa->foto);

            // Validate MIME type
            if (!str_starts_with($mimeType, 'image/')) {
                Log::warning('Invalid MIME type for photo', [
                    'id' => $id,
                    'mime_type' => $mimeType
                ]);
                return $this->returnDefaultAvatar();
            }

            Log::info('Serving photo successfully', [
                'id' => $id,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'file_size' => filesize($filePath)
            ]);

            // Return the image file
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=31536000', // 1 year cache
                'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT',
            ]);

        } catch (\Exception $e) {
            Log::error('Error serving photo', [
                'siswa_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->returnDefaultAvatar();
        }
    }

    /**
     * Return default avatar helper
     */
    private function returnDefaultAvatar()
    {
        $defaultPath = public_path('images/default-avatar.png');
        
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=86400', // 1 day cache
            ]);
        }

        // If no default avatar exists, create a basic SVG
        $svg = '<?xml version="1.0" encoding="UTF-8"?><svg width="150" height="150" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg"><rect width="150" height="150" fill="#e9ecef"/><circle cx="75" cy="60" r="25" fill="#6c757d"/>
        <ellipse cx="75" cy="120" rx="35" ry="25" fill="#6c757d"/><text x="75" y="140" text-anchor="middle" font-family="Arial" font-size="12" fill="#495057">No Photo</text></svg>';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Download photo - PUBLIC ACCESS
     */
    public function downloadPhoto($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            
            if (!$siswa->foto) {
                abort(404, 'Siswa tidak memiliki foto');
            }

            if (!Storage::disk('public')->exists($siswa->foto)) {
                abort(404, 'File foto tidak ditemukan');
            }

            // Generate download filename
            $extension = pathinfo($siswa->foto, PATHINFO_EXTENSION);
            $filename = Str::slug($siswa->nama_lengkap) . '_foto.' . $extension;

            Log::info('Photo download', [
                'siswa_id' => $id,
                'siswa_name' => $siswa->nama_lengkap,
                'filename' => $filename
            ]);

            return Storage::disk('public')->download($siswa->foto, $filename);

        } catch (\Exception $e) {
            Log::error('Error downloading photo', [
                'siswa_id' => $id,
                'error' => $e->getMessage()
            ]);
            abort(404, 'Foto tidak dapat diunduh');
        }
    }

    /**
     * Show delete confirmation page - PUBLIC ACCESS
     */
    public function delete($id)
    {
        $siswa = Siswa::findOrFail($id);
        $isAuthenticated = auth()->check();

        return view("siswa.delete", compact("siswa", "isAuthenticated"));
    }

    /**
     * Remove siswa - PUBLIC ACCESS (ROBUST IMPLEMENTATION)
     */
    public function destroy($id)
    {
        try {
            // Validate ID parameter
            if (!is_numeric($id) || $id <= 0) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ID siswa tidak valid'
                    ], 400);
                }
                return redirect()->back()->with("error", "ID siswa tidak valid");
            }

            // Find siswa with proper error handling
            $siswa = Siswa::find($id);
            if (!$siswa) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data siswa tidak ditemukan'
                    ], 404);
                }
                return redirect()->back()->with("error", "Data siswa tidak ditemukan");
            }

            // Store nama untuk message
            $nama = $siswa->nama_lengkap;

            // Delete photo if exists
            if ($siswa->foto && Storage::disk("public")->exists($siswa->foto)) {
                try {
                    Storage::disk("public")->delete($siswa->foto);
                    Log::info("Photo deleted successfully", ['photo_path' => $siswa->foto]);
                } catch (\Exception $photoError) {
                    Log::warning("Failed to delete photo", [
                        'photo_path' => $siswa->foto,
                        'error' => $photoError->getMessage()
                    ]);
                    // Continue with deletion even if photo deletion fails
                }
            }

            // Delete the record
            $deleted = $siswa->delete();
            if (!$deleted) {
                throw new \Exception("Gagal menghapus data dari database");
            }

            // Log successful deletion
            Log::info("Siswa deleted successfully", [
                'id' => $id,
                'nama' => $nama,
                'deleted_at' => now(),
                'user_agent' => request()->userAgent(),
                'ip_address' => request()->ip()
            ]);

            $successMessage = "Data siswa {$nama} berhasil dihapus";

            // Return JSON response untuk AJAX requests
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'data' => [
                        'deleted_id' => $id,
                        'deleted_name' => $nama
                    ]
                ], 200);
            }

            // Return redirect untuk form submission
            return redirect()->route("siswa.index")->with("success", $successMessage);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Siswa not found for deletion', ['id' => $id, 'error' => $e->getMessage()]);
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with("error", "Data siswa tidak ditemukan");

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during siswa deletion', [
                'id' => $id,
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

            $errorMessage = 'Gagal menghapus data karena masalah database';

            // Handle foreign key constraint errors
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                $errorMessage = 'Data tidak dapat dihapus karena masih digunakan di bagian lain sistem';
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return redirect()->back()->with("error", $errorMessage);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Unexpected error deleting siswa', [
                'id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Terjadi kesalahan sistem yang tidak terduga';

            // Don't expose internal errors in production
            if (config('app.debug')) {
                $errorMessage .= ': ' . $e->getMessage();
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'debug' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->back()->with("error", $errorMessage);
        }
    }

    // ==================== ADMIN & PUBLIC METHODS ====================

    /**
     * Show students by status - PUBLIC ACCESS dengan admin features
     */
    public function byStatus($status)
    {
        $siswa = Siswa::where('status_pendaftaran', $status)->latest()->paginate(10);
        $statusLabel = ucfirst($status);
        $isAuthenticated = auth()->check();

        return view("siswa.by-status", compact("siswa", "status", "statusLabel", "isAuthenticated"));
    }

    /**
     * Update student status - ADMIN ONLY (dilindungi middleware di routes)
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pendaftaran' => 'required|in:pending,diterima,ditolak',
            'keterangan' => 'nullable|string'
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update($validated);

        $statusText = [
            'pending' => 'pending',
            'diterima' => 'diterima',
            'ditolak' => 'ditolak'
        ];

        return redirect()->back()->with("success", "Status siswa berhasil diubah menjadi " . $statusText[$validated['status_pendaftaran']]);
    }

    /**
     * Bulk update status - ADMIN ONLY (dilindungi middleware di routes)
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswa,id',
            'status_pendaftaran' => 'required|in:pending,diterima,ditolak',
            'keterangan' => 'nullable|string'
        ]);

        Siswa::whereIn('id', $validated['siswa_ids'])
             ->update([
                 'status_pendaftaran' => $validated['status_pendaftaran'],
                 'keterangan' => $validated['keterangan']
             ]);

        $count = count($validated['siswa_ids']);
        $statusText = [
            'pending' => 'pending',
            'diterima' => 'diterima',
            'ditolak' => 'ditolak'
        ];

        return redirect()->back()->with("success", "{$count} siswa berhasil diubah statusnya menjadi " . $statusText[$validated['status_pendaftaran']]);
    }

    /**
     * Export data siswa - PUBLIC ACCESS (data terbatas untuk non-admin)
     */
    public function export(Request $request)
    {
        $query = Siswa::query();

        // Batasi akses data untuk non-admin
        if (!auth()->check()) {
            $query->where('status_pendaftaran', 'diterima')
                  ->select(['nisn', 'nama_lengkap', 'email', 'status_pendaftaran']);
        } else {
            // Admin bisa export semua data
            if ($request->has('status') && $request->status) {
                $query->where('status_pendaftaran', $request->status);
            }
        }

        $siswa = $query->get();

        return response()->json($siswa);
    }

    /**
     * Public list view - PUBLIC ACCESS (hanya siswa diterima untuk non-admin)
     */
    public function list(Request $request)
    {
        $query = Siswa::query();

        // Hanya tampilkan siswa yang diterima untuk non-admin
        if (!auth()->check()) {
            $query->where('status_pendaftaran', 'diterima');
        }

        // Search functionality
        if ($request->has("search") && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where("nama_lengkap", "like", "%" . $request->search . "%")
                  ->orWhere('nisn', 'like', "%" . $request->search . "%");
            });
        }

        $siswa = $query->latest()->paginate(12);
        $isAuthenticated = auth()->check();

        return view("siswa.list", compact("siswa", "isAuthenticated"));
    }

    /**
     * Validate student data completeness - PUBLIC ACCESS
     */
    public function validateData($id)
    {
        $siswa = Siswa::findOrFail($id);

        // Simple validation check
        $requiredFields = ['nisn', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'email'];
        $isComplete = true;

        foreach ($requiredFields as $field) {
            if (empty($siswa->$field)) {
                $isComplete = false;
                break;
            }
        }

        return response()->json([
            'is_complete' => $isComplete,
            'message' => $isComplete ? 'Data siswa lengkap' : 'Data siswa belum lengkap'
        ]);
    }

    /**
     * Bulk delete siswa - ADMIN ONLY
     */
    public function bulkDestroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'siswa_ids' => 'required|array|min:1',
                'siswa_ids.*' => 'required|integer|exists:siswa,id'
            ]);

            $siswaIds = $validated['siswa_ids'];
            $siswaList = Siswa::whereIn('id', $siswaIds)->get();

            if ($siswaList->isEmpty()) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada data siswa yang valid untuk dihapus'
                    ], 404);
                }
                return redirect()->back()->with("error", "Tidak ada data siswa yang valid untuk dihapus");
            }

            $deletedCount = 0;
            $errors = [];

            foreach ($siswaList as $siswa) {
                try {
                    // Delete photo if exists
                    if ($siswa->foto && Storage::disk("public")->exists($siswa->foto)) {
                        Storage::disk("public")->delete($siswa->foto);
                    }

                    $siswa->delete();
                    $deletedCount++;

                } catch (\Exception $e) {
                    $errors[] = "Gagal menghapus {$siswa->nama_lengkap}: " . $e->getMessage();
                    Log::error('Error in bulk delete', [
                        'siswa_id' => $siswa->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info("Bulk delete completed", [
                'total_requested' => count($siswaIds),
                'successfully_deleted' => $deletedCount,
                'errors_count' => count($errors)
            ]);

            $message = "{$deletedCount} siswa berhasil dihapus";
            if (!empty($errors)) {
                $message .= ". Terjadi " . count($errors) . " kesalahan.";
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => $deletedCount > 0,
                    'message' => $message,
                    'data' => [
                        'deleted_count' => $deletedCount,
                        'total_requested' => count($siswaIds),
                        'errors' => $errors
                    ]
                ]);
            }

            $redirectType = $deletedCount > 0 ? 'success' : 'error';
            return redirect()->back()->with($redirectType, $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dikirim tidak valid',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->with('error', 'Data yang dikirim tidak valid');

        } catch (\Exception $e) {
            Log::error('Bulk delete error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            $errorMessage = 'Terjadi kesalahan saat menghapus data secara massal';

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return redirect()->back()->with("error", $errorMessage);
        }
    }
    /**
     * Statistics page with grade analysis - UPDATED FOR PUBLIC ACCESS
     */
    public function statistikNilai() 
    {
        $isAuthenticated = auth()->check();
        
        try {
            // Get statistics for all students (not just accepted ones for public view)
            $totalSiswa = Siswa::count();
            
            if ($totalSiswa == 0) {
                // No students data available
                $stats = [
                    'total_siswa' => 0,
                    'rata_rata_nilai' => 0,
                    'persentase_kelulusan' => 0,
                    'distribusi_nilai' => [
                        '90-100' => 0,
                        '80-89' => 0,
                        '70-79' => 0,
                        '60-69' => 0,
                        'Below 60' => 0
                    ],
                    'performa_akademik' => [
                        'diterima' => 0,
                        'ditolak' => 0,
                        'menunggu' => 0
                    ]
                ];
                
                return view("statistik", compact("stats", "isAuthenticated"));
            }
            
            // Get all students with valid grades for calculations
            $siswaData = Siswa::whereNotNull('nilai_ujian_nasional')
                            ->where('nilai_ujian_nasional', '>', 0)
                            ->get();
            
            // Calculate average grade from all students with valid grades
            $rataRataNilai = $siswaData->avg('nilai_ujian_nasional') ?: 0;
            
            // Calculate pass percentage based on accepted students
            $siswaAccepted = Siswa::where('status_pendaftaran', 'diterima')->count();
            $persentaseKelulusan = $totalSiswa > 0 ? ($siswaAccepted / $totalSiswa) * 100 : 0;
            
            // Grade distribution from all students with valid grades
            $distribusiNilai = [
                '90-100' => $siswaData->whereBetween('nilai_ujian_nasional', [90, 100])->count(),
                '80-89' => $siswaData->whereBetween('nilai_ujian_nasional', [80, 89])->count(),
                '70-79' => $siswaData->whereBetween('nilai_ujian_nasional', [70, 79])->count(),
                '60-69' => $siswaData->whereBetween('nilai_ujian_nasional', [60, 69])->count(),
                'Below 60' => $siswaData->where('nilai_ujian_nasional', '<', 60)->count(),
            ];
            
            // Academic performance by status
            $diterima = Siswa::where('status_pendaftaran', 'diterima')->count();
            $ditolak = Siswa::where('status_pendaftaran', 'ditolak')->count();
            $menunggu = Siswa::where('status_pendaftaran', 'pending')->count();
            
            $performaAkademik = [
                'diterima' => $diterima,
                'ditolak' => $ditolak,
                'menunggu' => $menunggu
            ];
            
            $stats = [
                'total_siswa' => $totalSiswa,
                'rata_rata_nilai' => round($rataRataNilai, 1),
                'persentase_kelulusan' => round($persentaseKelulusan, 0),
                'distribusi_nilai' => $distribusiNilai,
                'performa_akademik' => $performaAkademik
            ];
            
            Log::info('Grade statistics calculated', [
                'total_siswa' => $totalSiswa,
                'rata_rata' => $rataRataNilai,
                'persentase_lulus' => $persentaseKelulusan,
                'is_authenticated' => $isAuthenticated
            ]);
            
            return view("statistik", compact("stats", "isAuthenticated"));
            
        } catch (\Exception $e) {
            Log::error('Error calculating grade statistics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return default stats on error
            $stats = [
                'total_siswa' => 0,
                'rata_rata_nilai' => 0,
                'persentase_kelulusan' => 0,
                'distribusi_nilai' => [
                    '90-100' => 0,
                    '80-89' => 0,
                    '70-79' => 0,
                    '60-69' => 0,
                    'Below 60' => 0
                ],
                'performa_akademik' => [
                    'diterima' => 0,
                    'ditolak' => 0,
                    'menunggu' => 0
                ]
            ];
            
            return view("statistik", compact("stats", "isAuthenticated"))
                ->with('error', 'Terjadi kesalahan saat memuat statistik');
        }
    }

    /**
     * Updated statistik method to use grade-based statistics - PUBLIC ACCESS
     */
    public function statistik() 
    {
        return $this->statistikNilai();
    }

    /**
     * Dashboard statistics - UPDATED FOR PUBLIC ACCESS
     */
    public function dashboard()
    {
        $isAuthenticated = auth()->check();

        // Get basic statistics for both admin and public
        $totalSiswa = Siswa::count();
        $totalDiterima = Siswa::where('status_pendaftaran', 'diterima')->count();
        $totalDitolak = Siswa::where('status_pendaftaran', 'ditolak')->count();
        $totalPending = Siswa::where('status_pendaftaran', 'pending')->count();

        if ($isAuthenticated) {
            // Full statistics untuk admin
            $stats = [
                'total' => $totalSiswa,
                'pending' => $totalPending,
                'diterima' => $totalDiterima,
                'ditolak' => $totalDitolak,
            ];
            $recentSiswa = Siswa::latest()->take(5)->get();
        } else {
            // Public statistics (show all data but in different format)
            $stats = [
                'total_siswa' => $totalSiswa,
                'total_diterima' => $totalDiterima,
                'total_ditolak' => $totalDitolak,
                'total_pending' => $totalPending,
            ];
            $recentSiswa = Siswa::where('status_pendaftaran', 'diterima')->latest()->take(5)->get();
        }

        return view("dashboard", compact("stats", "recentSiswa", "isAuthenticated"));
    }
}
