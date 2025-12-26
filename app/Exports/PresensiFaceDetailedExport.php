<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiFaceDetailedExport implements WithMultipleSheets
{
    protected $filters;
    protected $tanggal_awal;
    protected $tanggal_akhir;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->tanggal_awal = $filters['tanggal_awal'] ?? null;
        $this->tanggal_akhir = $filters['tanggal_akhir'] ?? null;
    }

    /**
     * ✅ Create multiple sheets - one per employee
     */
    public function sheets(): array
    {
        $sheets = [];

        // ✅ Get karyawan based on filters
        $karyawanQuery = DB::table('karyawan as k')
            ->leftJoin('departemen as d', 'k.kode_dept', '=', 'd.kode_dept')
            ->leftJoin('cabang as c', 'k.kode_cabang', '=', 'c.kode_cabang')
            ->select(
                'k.nik',
                'k.nama_lengkap',
                'k.jabatan',
                'k.kode_dept',
                'k.kode_cabang',
                'd.nama_dept',
                'c.nama_cabang'
            );

        // ✅ PRIORITY 1: Filter by specific NIK list (if provided)
        if (!empty($this->filters['nik_list']) && is_array($this->filters['nik_list'])) {
            $karyawanQuery->whereIn('k.nik', $this->filters['nik_list']);
            
            Log::info('Export - Filtering by specific NIK list', [
                'nik_count' => count($this->filters['nik_list']),
                'niks' => $this->filters['nik_list']
            ]);
        } else {
            // ✅ PRIORITY 2: Apply cabang/dept filters only if no specific NIK selected
            if (!empty($this->filters['kode_cabang'])) {
                $karyawanQuery->where('k.kode_cabang', $this->filters['kode_cabang']);
            }

            if (!empty($this->filters['kode_dept'])) {
                $karyawanQuery->where('k.kode_dept', $this->filters['kode_dept']);
            }
        }

        $karyawanList = $karyawanQuery->orderBy('k.nama_lengkap')->get();

        Log::info('Export - Total Karyawan', [
            'total' => $karyawanList->count(),
            'filters' => $this->filters
        ]);

        // ✅ Create sheet for each karyawan
        foreach ($karyawanList as $index => $karyawan) {
            $sheets[] = new PresensiFaceEmployeeSheet(
                $karyawan,
                $this->tanggal_awal,
                $this->tanggal_akhir,
                $index + 1
            );
        }

        // ✅ Add summary sheet at the beginning
        array_unshift($sheets, new PresensiFaceSummarySheet(
            $karyawanList,
            $this->tanggal_awal,
            $this->tanggal_akhir,
            $this->filters
        ));

        return $sheets;
    }
}

/**
 * ✅ Sheet 1: Summary Sheet (All Employees Overview)
 */
class PresensiFaceSummarySheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $karyawanList;
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $filters;

    public function __construct($karyawanList, $tanggal_awal, $tanggal_akhir, $filters)
    {
        $this->karyawanList = $karyawanList;
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->filters = $filters;
    }

    public function collection()
    {
        $data = collect();

        foreach ($this->karyawanList as $karyawan) {
            // Get presensi count
            $presensiQuery = DB::table('presensi_face')
                ->where('nik', $karyawan->nik);

            if ($this->tanggal_awal) {
                $presensiQuery->where('tanggal', '>=', $this->tanggal_awal);
            }
            if ($this->tanggal_akhir) {
                $presensiQuery->where('tanggal', '<=', $this->tanggal_akhir);
            }

            $totalPresensi = $presensiQuery->count();
            $totalVerified = $presensiQuery->where('status', 'verified')->count();
            $totalFailed = $presensiQuery->where('status', 'failed')->count();
            $totalMultiShift = $presensiQuery->whereNotNull('shift_ke')->count();
            $totalRegular = $presensiQuery->whereNull('shift_ke')->count();

            // Get jam kerja config
            $jamKerjaConfig = $this->getJamKerjaConfig($karyawan->kode_dept, $karyawan->kode_cabang);

            $data->push([
                $karyawan->nik,
                $karyawan->nama_lengkap,
                $karyawan->jabatan,
                $karyawan->nama_dept,
                $karyawan->nama_cabang,
                $jamKerjaConfig['tipe'] ?? '-',
                $jamKerjaConfig['nama'] ?? '-',
                $totalPresensi,
                $totalVerified,
                $totalFailed,
                $totalMultiShift,
                $totalRegular,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Lengkap',
            'Jabatan',
            'Departemen',
            'Cabang',
            'Tipe Jam Kerja',
            'Nama Jam Kerja',
            'Total Presensi',
            'Verified',
            'Failed',
            'Multi-Shift',
            'Regular',
        ];
    }

    public function title(): string
    {
        return 'Summary';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 15,
            'G' => 20,
            'H' => 12,
            'I' => 12,
            'J' => 12,
            'K' => 12,
            'L' => 12,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0054a6']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    private function getJamKerjaConfig($kode_dept, $kode_cabang)
    {
        $config = DB::table('konfigurasi_jk_dept as kjd')
            ->join('konfigurasi_jk_dept_detail as kjdd', 'kjd.kode_jk_dept', '=', 'kjdd.kode_jk_dept')
            ->join('jam_kerja as jk', 'kjdd.kode_jam_kerja', '=', 'jk.kode_jam_kerja')
            ->where('kjd.kode_dept', $kode_dept)
            ->where('kjd.kode_cabang', $kode_cabang)
            ->select('jk.kode_jam_kerja', 'jk.nama_jam_kerja', 'jk.tipe_jam_kerja', 'jk.total_shift')
            ->first();

        if ($config) {
            return [
                'kode' => $config->kode_jam_kerja,
                'nama' => $config->nama_jam_kerja,
                'tipe' => $config->tipe_jam_kerja === 'multi_shift' ? 'Multi-Shift' : 'Regular',
                'total_shift' => $config->total_shift
            ];
        }

        return [];
    }
}

/**
 * ✅ Sheet 2+: Individual Employee Sheet with Jam Kerja Details
 */
class PresensiFaceEmployeeSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $karyawan;
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $sheetIndex;

    public function __construct($karyawan, $tanggal_awal, $tanggal_akhir, $sheetIndex)
    {
        $this->karyawan = $karyawan;
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->sheetIndex = $sheetIndex;
    }

    public function collection()
    {
        // ✅ Get jam kerja configuration
        $jamKerjaConfig = $this->getJamKerjaConfig();

        // ✅ Get presensi data
        $presensiQuery = DB::table('presensi_face')
            ->where('nik', $this->karyawan->nik);

        if ($this->tanggal_awal) {
            $presensiQuery->where('tanggal', '>=', $this->tanggal_awal);
        }
        if ($this->tanggal_akhir) {
            $presensiQuery->where('tanggal', '<=', $this->tanggal_akhir);
        }

        $presensiData = $presensiQuery
            ->orderBy('tanggal', 'desc')
            ->orderBy('shift_ke', 'asc')
            ->get();

        $data = collect();

        // ✅ Add employee info header
        $data->push([
            'INFORMASI KARYAWAN',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]);
        $data->push([
            'NIK',
            $this->karyawan->nik,
            '',
            'Nama',
            $this->karyawan->nama_lengkap,
            '',
            '',
            '',
            ''
        ]);
        $data->push([
            'Jabatan',
            $this->karyawan->jabatan,
            '',
            'Departemen',
            $this->karyawan->nama_dept,
            '',
            '',
            '',
            ''
        ]);
        $data->push([
            'Cabang',
            $this->karyawan->nama_cabang,
            '',
            'Tipe Jam Kerja',
            $jamKerjaConfig['tipe'] ?? '-',
            '',
            '',
            '',
            ''
        ]);

        // ✅ Add spacing
        $data->push(['', '', '', '', '', '', '', '', '']);

        // ✅ Add presensi data header
        $data->push([
            'DATA PRESENSI',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]);

        // Add presensi column headers (will be styled)
        $data->push([
            'No',
            'Tanggal',
            'Hari',
            'Shift',
            'Nama Shift',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Lokasi'
        ]);

        // ✅ Add presensi records
        foreach ($presensiData as $index => $item) {
            $tanggal = Carbon::parse($item->tanggal);
            $data->push([
                $index + 1,
                $tanggal->format('d/m/Y'),
                $tanggal->locale('id')->isoFormat('dddd'),
                $item->shift_ke ? 'Shift ' . $item->shift_ke : 'Regular',
                $item->nama_shift ?? '-',
                $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '-',
                $item->jam_pulang ? substr($item->jam_pulang, 0, 5) : '-',
                $item->status === 'verified' ? 'Verified' : 'Failed',
                $item->lokasi ?? '-'
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        // No headings - we add them manually in collection
        return [];
    }

    public function title(): string
    {
        // Excel sheet name limit: 31 characters
        $name = $this->karyawan->nama_lengkap;
        if (strlen($name) > 25) {
            $name = substr($name, 0, 25);
        }
        return $this->sheetIndex . '. ' . $name;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 15,
            'C' => 12,
            'D' => 12,
            'E' => 20,
            'F' => 12,
            'G' => 12,
            'H' => 12,
            'I' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the sheet
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0054a6']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Style presensi table header (dynamic row - find "DATA PRESENSI")
        // This is a simplified approach - you may need to adjust based on actual row numbers

        return [];
    }

    private function getJamKerjaConfig()
    {
        $config = DB::table('konfigurasi_jk_dept as kjd')
            ->join('konfigurasi_jk_dept_detail as kjdd', 'kjd.kode_jk_dept', '=', 'kjdd.kode_jk_dept')
            ->join('jam_kerja as jk', 'kjdd.kode_jam_kerja', '=', 'jk.kode_jam_kerja')
            ->where('kjd.kode_dept', $this->karyawan->kode_dept)
            ->where('kjd.kode_cabang', $this->karyawan->kode_cabang)
            ->select('jk.kode_jam_kerja', 'jk.nama_jam_kerja', 'jk.tipe_jam_kerja', 'jk.total_shift')
            ->first();

        if ($config) {
            return [
                'kode' => $config->kode_jam_kerja,
                'nama' => $config->nama_jam_kerja,
                'tipe' => $config->tipe_jam_kerja === 'multi_shift' ? 'Multi-Shift' : 'Regular',
                'total_shift' => $config->total_shift
            ];
        }

        return [];
    }
}