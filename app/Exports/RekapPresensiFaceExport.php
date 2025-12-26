<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapPresensiFaceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $rekap;
    protected $bulan;
    protected $tahun;

    public function __construct($rekap, $bulan, $tahun)
    {
        $this->rekap = $rekap;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return $this->rekap;
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Lengkap',
            'Jabatan',
            'Cabang',
            'Departemen',
            'Total Regular',
            'Total Multi-Shift',
            'Total Hadir',
            'Total Verified',
            'Total Failed',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->nik,
            $item->nama_lengkap,
            $item->jabatan ?? '-',
            $item->nama_cabang ?? '-',
            $item->nama_dept ?? '-',
            $item->total_hadir_regular ?? 0,
            $item->total_hadir_multi ?? 0,
            $item->total_hadir ?? 0,
            $item->total_verified ?? 0,
            $item->total_failed ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }

    public function title(): string
    {
        $namabulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return 'Rekap ' . $namabulan[$this->bulan] . ' ' . $this->tahun;
    }
}