<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaceData;
use App\Models\Karyawan;
use App\Models\Branch;
use App\Models\Unit;
use App\Models\Organ;
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
            $user = auth('user')->user();
            $query = Karyawan::with(['organ.unit.branch', 'faceData'])
                ->select('karyawan.*')
                ->leftJoin('organs', 'karyawan.organ_id', '=', 'organs.id')
                ->leftJoin('units', 'organs.unit_id', '=', 'units.id');

            if ($user && $user->role === 'admin') {
                $query->where('units.branch_id', $user->branch_id);
            }

            // Filter by branch
            if ($request->filled('branch_id')) {
                $query->where('units.branch_id', $request->branch_id);
            }

            // Filter by unit
            if ($request->filled('unit_id')) {
                $query->where('units.id', $request->unit_id);
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
                    $q->where('karyawan.nik', 'like', '%' . $search . '%')
                        ->orWhere('karyawan.nama_lengkap', 'like', '%' . $search . '%');
                });
            }

            $karyawan = $query->orderBy('karyawan.nama_lengkap', 'ASC')->paginate(20);

            // Get filter data
            if ($user && $user->role === 'admin') {
                $branches = Branch::where('id', $user->branch_id)->get();
                $units = Unit::where('branch_id', $user->branch_id)->orderBy('name')->get();
            } else {
                $branches = Branch::orderBy('name')->get();
                $units = Unit::orderBy('name')->get();
            }

            // Get statistics
            $stats = $this->getStatistics();

            return view('admin.face-verification.index', compact('karyawan', 'branches', 'units', 'stats'));
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
            $user = auth('user')->user();
            $karyawanQuery = Karyawan::with(['organ.unit.branch', 'faceData']);
            
            if ($user && $user->role === 'admin') {
                $karyawanQuery->whereHas('organ.unit', function($q) use ($user) {
                    $q->where('branch_id', $user->branch_id);
                });
            }
            
            $karyawan = $karyawanQuery->findOrFail($nik);

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
            $user = auth('user')->user();
            $karyawan = Karyawan::with('organ.unit')->where('nik', $nik)->first();
            if ($user && $user->role === 'admin' && $karyawan && $karyawan->branch_id !== $user->branch_id) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

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
            $user = auth('user')->user();
            $karyawan = Karyawan::with('organ.unit')->where('nik', $nik)->first();
            if ($user && $user->role === 'admin' && $karyawan && $karyawan->branch_id !== $user->branch_id) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

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

            $user = auth('user')->user();
            $karyawan = Karyawan::with('organ.unit')->where('nik', $nik)->first();
            if ($user && $user->role === 'admin' && $karyawan && $karyawan->branch_id !== $user->branch_id) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

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

            $user = auth('user')->user();
            if ($user && $user->role === 'admin') {
                $invalid = Karyawan::whereIn('nik', $request->nik_list)
                    ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
                    ->join('units', 'organs.unit_id', '=', 'units.id')
                    ->where('units.branch_id', '!=', $user->branch_id)
                    ->count();
                if ($invalid > 0) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
            }

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

            $user = auth('user')->user();
            if ($user && $user->role === 'admin') {
                $invalid = Karyawan::whereIn('nik', $request->nik_list)
                    ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
                    ->join('units', 'organs.unit_id', '=', 'units.id')
                    ->where('units.branch_id', '!=', $user->branch_id)
                    ->count();
                if ($invalid > 0) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
            }

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
            $user = auth('user')->user();
            $query = Karyawan::with(['organ.unit.branch', 'faceData'])
                ->select('karyawan.*')
                ->leftJoin('organs', 'karyawan.organ_id', '=', 'organs.id')
                ->leftJoin('units', 'organs.unit_id', '=', 'units.id');

            if ($user && $user->role === 'admin') {
                $query->where('units.branch_id', $user->branch_id);
            }

            // Apply same filters as index
            if ($request->filled('branch_id')) {
                $query->where('units.branch_id', $request->branch_id);
            }

            if ($request->filled('unit_id')) {
                $query->where('units.id', $request->unit_id);
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

            $karyawan = $query->orderBy('karyawan.nama_lengkap', 'ASC')->get();

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
        $user = auth('user')->user();

        $karyawanQuery = Karyawan::query()
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id');

        if ($user && $user->role === 'admin') {
            $karyawanQuery->where('units.branch_id', $user->branch_id);
        }
        $totalKaryawan = $karyawanQuery->count();

        $enrolledQuery = FaceData::where('status', 'active');
        if ($user && $user->role === 'admin') {
            $enrolledQuery->whereHas('karyawan.organ.unit', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }
        $enrolled = $enrolledQuery->count();

        $inactiveQuery = FaceData::where('status', 'inactive');
        if ($user && $user->role === 'admin') {
            $inactiveQuery->whereHas('karyawan.organ.unit', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }
        $inactive = $inactiveQuery->count();

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
            $user = auth('user')->user();
            $karyawan = Karyawan::with('organ.unit')->where('nik', $nik)->first();
            if ($user && $user->role === 'admin' && $karyawan && $karyawan->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized action.');
            }

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
