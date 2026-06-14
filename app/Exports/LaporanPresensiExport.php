<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPresensiExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $presensi;
    protected $bulan;
    protected $tahun;
    protected $namabulan;

    public function __construct($presensi, $bulan, $tahun)
    {
        $this->presensi = $presensi;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    }

    public function view(): View
    {
        return view('admin.laporan.excel', [
            'presensi' => $this->presensi,
            'namabulan' => $this->namabulan[$this->bulan],
            'tahun' => $this->tahun
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            3    => ['font' => ['bold' => true]],
        ];
    }
}
