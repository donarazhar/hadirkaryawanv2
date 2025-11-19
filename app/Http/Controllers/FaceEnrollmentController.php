<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\FaceData;
use Exception;

class FaceEnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:karyawan');
    }

    /**
     * Tampilkan halaman pendaftaran wajah
     */
    public function index()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        
        // Cek apakah sudah pernah mendaftar
        $faceData = FaceData::where('nik', $nik)->first();
        
        return view('karyawan.face.enrollment', compact('faceData'));
    }

    /**
     * Simpan data wajah
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'face_descriptor' => 'required|string',
                'face_image' => 'required|string'
            ]);

            $nik = Auth::guard('karyawan')->user()->nik;
            
            DB::beginTransaction();

            // Simpan gambar wajah
            $image = $request->face_image;
            $image_parts = explode(";base64,", $image);
            
            if (count($image_parts) < 2) {
                throw new Exception('Format gambar tidak valid');
            }

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $nik . '_face_' . time() . '.png';
            $folderPath = "public/uploads/faces/";
            $file = $folderPath . $fileName;
            
            Storage::put($file, $image_base64);

            // Simpan atau update face data
            $faceData = FaceData::updateOrCreate(
                ['nik' => $nik],
                [
                    'face_descriptor' => $request->face_descriptor,
                    'face_image' => $fileName,
                    'status' => 'active',
                    'enrollment_count' => DB::raw('enrollment_count + 1'),
                    'last_updated' => now()
                ]
            );

            DB::commit();

            Log::info('Face enrollment success', [
                'nik' => $nik,
                'enrollment_count' => $faceData->enrollment_count
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data wajah berhasil disimpan'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Face enrollment error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data wajah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get face descriptor untuk verifikasi
     */
    public function getDescriptor()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            
            $faceData = FaceData::where('nik', $nik)
                ->where('status', 'active')
                ->first();

            if (!$faceData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data wajah belum terdaftar'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'descriptor' => json_decode($faceData->face_descriptor)
            ]);

        } catch (Exception $e) {
            Log::error('Get face descriptor error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data wajah'
            ], 500);
        }
    }

    /**
     * Delete face data
     */
    public function destroy()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            
            $faceData = FaceData::where('nik', $nik)->first();
            
            if ($faceData) {
                // Delete image file
                if ($faceData->face_image) {
                    Storage::delete('public/uploads/faces/' . $faceData->face_image);
                }
                
                $faceData->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Data wajah berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data wajah tidak ditemukan'
            ], 404);

        } catch (Exception $e) {
            Log::error('Delete face data error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data wajah'
            ], 500);
        }
    }
}