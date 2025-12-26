<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiFaceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $presensi;
    protected $stats;
    protected $request;

    public function __construct($presensi, $stats, $request)
    {
        $this->presensi = $presensi;
        $this->stats = $stats;
        $this->request = $request;
    }

    public function collection()
    {
        return $this->presensi;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'NIK',
            'Nama Lengkap',
            'Jabatan',
            'Cabang',
            'Departemen',
            'Shift Ke',
            'Nama Shift',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Lokasi GPS',
        ];
    }

    public function map($presensi): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $presensi->tanggal ? \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y') : '-',
            $presensi->nik,
            $presensi->karyawan->nama_lengkap ?? 'N/A',
            $presensi->karyawan->jabatan ?? '-',
            $presensi->karyawan->cabang->nama_cabang ?? '-',
            $presensi->karyawan->departemen->nama_dept ?? '-',
            $presensi->shift_ke ?? '-',
            $presensi->nama_shift ?? '-',
            $presensi->jam_masuk ? \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') : '-',
            $presensi->jam_pulang ? \Carbon\Carbon::parse($presensi->jam_pulang)->format('H:i') : '-',
            $presensi->status == 'verified' ? 'Verified' : 'Failed',
            $presensi->lokasi ?? '-',
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
        return 'Data Presensi Face';
    }
}
