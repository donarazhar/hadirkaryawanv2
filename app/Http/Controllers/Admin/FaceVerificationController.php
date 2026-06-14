<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaceData;
use App\Models\Karyawan;
use App\Models\Cabang;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FaceVerificationController extends Controller
{
    /**
     * Display listing of face verification with enrollment status
     */
    public function index(Request $request)
    {
        try {
            $query = Karyawan::with(['departemen', 'cabang', 'faceData'])
                ->select('karyawan.*');

            // Filter by cabang
            if ($request->filled('kode_cabang')) {
                $query->where('kode_cabang', $request->kode_cabang);
            }

            // Filter by departemen
            if ($request->filled('kode_dept')) {
                $query->where('kode_dept', $request->kode_dept);
            }

            // Filter by enrollment status
            if ($request->filled('status')) {
                if ($request->status === 'enrolled') {
                    $query->whereHas('faceData', function ($q) {
                        $q->where('status', 'active');
                    });
                } elseif ($request->status === 'not_enrolled') {
                    $query->whereDoesntHave('faceData');
                } elseif ($request->status === 'inactive') {
                    $query->whereHas('faceData', function ($q) {
                        $q->where('status', 'inactive');
                    });
                }
            }

            // Search by nama atau NIK
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nik', 'like', '%' . $search . '%')
                        ->orWhere('nama_lengkap', 'like', '%' . $search . '%');
                });
            }

            $karyawan = $query->orderBy('nama_lengkap', 'ASC')->paginate(20);

            // Get filter data
            $cabang = Cabang::orderBy('nama_cabang')->get();
            $departemen = Departemen::orderBy('nama_dept')->get();

            // Get statistics
            $stats = $this->getStatistics();

            return view('admin.face-verification.index', compact('karyawan', 'cabang', 'departemen', 'stats'));
        } catch (\Exception $e) {
            Log::error('FaceVerification@index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    /**
     * Show detail face verification
     */
    public function show($nik)
    {
        try {
            $karyawan = Karyawan::with(['departemen', 'cabang', 'faceData'])
                ->findOrFail($nik);

            // Get presensi history using face
            $presensiHistory = DB::table('presensi_face')
                ->where('nik', $nik)
                ->orderBy('tanggal', 'DESC')
                ->limit(10)
                ->get();

            return view('admin.face-verification.show', compact('karyawan', 'presensiHistory'));
        } catch (\Exception $e) {
            Log::error('FaceVerification@show Error: ' . $e->getMessage());
            return redirect()->route('panel.face-verification.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Activate face data
     */
    public function activate($nik)
    {
        try {
            $faceData = FaceData::where('nik', $nik)->first();

            if (!$faceData) {
                return redirect()->back()->with('error', 'Data wajah tidak ditemukan');
            }

            $faceData->update(['status' => 'active']);

            Log::info('Face data activated', ['nik' => $nik]);

            return redirect()->back()->with('success', 'Data wajah berhasil diaktifkan');
        } catch (\Exception $e) {
            Log::error('FaceVerification@activate Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengaktifkan data wajah');
        }
    }

    /**
     * Deactivate face data
     */
    public function deactivate($nik)
    {
        try {
            $faceData = FaceData::where('nik', $nik)->first();

            if (!$faceData) {
                return redirect()->back()->with('error', 'Data wajah tidak ditemukan');
            }

            $faceData->update(['status' => 'inactive']);

            Log::info('Face data deactivated', ['nik' => $nik]);

            return redirect()->back()->with('success', 'Data wajah berhasil dinonaktifkan');
        } catch (\Exception $e) {
            Log::error('FaceVerification@deactivate Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menonaktifkan data wajah');
        }
    }

    /**
     * Delete face data (reset enrollment)
     */
    public function destroy($nik)
    {
        try {
            DB::beginTransaction();

            $faceData = FaceData::where('nik', $nik)->first();

            if (!$faceData) {
                return redirect()->back()->with('error', 'Data wajah tidak ditemukan');
            }

            // Delete face image file
            if ($faceData->face_image) {
                Storage::delete('public/uploads/faces/' . $faceData->face_image);
            }

            // Delete face data
            $faceData->delete();

            DB::commit();

            Log::info('Face data deleted', ['nik' => $nik]);

            return redirect()->back()->with('success', 'Akses pendaftaran ulang dibuka. Karyawan sekarang dapat merekam wajah baru di HP mereka.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FaceVerification@destroy Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data wajah');
        }
    }

    /**
     * Bulk activate face data
     */
    public function bulkActivate(Request $request)
    {
        try {
            $request->validate([
                'nik_list' => 'required|array',
                'nik_list.*' => 'exists:face_data,nik'
            ]);

            $updated = FaceData::whereIn('nik', $request->nik_list)
                ->update(['status' => 'active']);

            Log::info('Bulk face activation', [
                'count' => $updated,
                'niks' => $request->nik_list
            ]);

            return response()->json([
                'success' => true,
                'message' => "$updated data wajah berhasil diaktifkan"
            ]);
        } catch (\Exception $e) {
            Log::error('FaceVerification@bulkActivate Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan data wajah'
            ], 500);
        }
    }

    /**
     * Bulk deactivate face data
     */
    public function bulkDeactivate(Request $request)
    {
        try {
            $request->validate([
                'nik_list' => 'required|array',
                'nik_list.*' => 'exists:face_data,nik'
            ]);

            $updated = FaceData::whereIn('nik', $request->nik_list)
                ->update(['status' => 'inactive']);

            Log::info('Bulk face deactivation', [
                'count' => $updated,
                'niks' => $request->nik_list
            ]);

            return response()->json([
                'success' => true,
                'message' => "$updated data wajah berhasil dinonaktifkan"
            ]);
        } catch (\Exception $e) {
            Log::error('FaceVerification@bulkDeactivate Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan data wajah'
            ], 500);
        }
    }

    /**
     * Export face verification report
     */
    public function export(Request $request)
    {
        try {
            $query = Karyawan::with(['departemen', 'cabang', 'faceData'])
                ->select('karyawan.*');

            // Apply same filters as index
            if ($request->filled('kode_cabang')) {
                $query->where('kode_cabang', $request->kode_cabang);
            }

            if ($request->filled('kode_dept')) {
                $query->where('kode_dept', $request->kode_dept);
            }

            if ($request->filled('status')) {
                if ($request->status === 'enrolled') {
                    $query->whereHas('faceData', function ($q) {
                        $q->where('status', 'active');
                    });
                } elseif ($request->status === 'not_enrolled') {
                    $query->whereDoesntHave('faceData');
                } elseif ($request->status === 'inactive') {
                    $query->whereHas('faceData', function ($q) {
                        $q->where('status', 'inactive');
                    });
                }
            }

            $karyawan = $query->orderBy('nama_lengkap', 'ASC')->get();

            $filename = 'Laporan_Verifikasi_Wajah_' . date('d-m-Y') . '.xls';

            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename={$filename}");

            return view('admin.face-verification.export', compact('karyawan'));
        } catch (\Exception $e) {
            Log::error('FaceVerification@export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data');
        }
    }

    /**
     * Get statistics
     */
    private function getStatistics()
    {
        $totalKaryawan = Karyawan::count();

        $enrolled = FaceData::where('status', 'active')->count();

        $inactive = FaceData::where('status', 'inactive')->count();

        $notEnrolled = $totalKaryawan - ($enrolled + $inactive);

        $percentage = $totalKaryawan > 0
            ? round(($enrolled / $totalKaryawan) * 100, 2)
            : 0;

        return [
            'total_karyawan' => $totalKaryawan,
            'enrolled' => $enrolled,
            'inactive' => $inactive,
            'not_enrolled' => $notEnrolled,
            'percentage' => $percentage
        ];
    }

    /**
     * Get statistics API
     */
    public function getStats()
    {
        try {
            $stats = $this->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('FaceVerification@getStats Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    /**
     * View face image
     */
    public function viewImage($nik)
    {
        try {
            $faceData = FaceData::where('nik', $nik)->firstOrFail();

            if (!$faceData->face_image) {
                abort(404, 'Gambar tidak ditemukan');
            }

            $path = storage_path('app/public/uploads/faces/' . $faceData->face_image);

            if (!file_exists($path)) {
                abort(404, 'File gambar tidak ditemukan');
            }

            return response()->file($path);
        } catch (\Exception $e) {
            Log::error('FaceVerification@viewImage Error: ' . $e->getMessage());
            abort(404);
        }
    }
}
