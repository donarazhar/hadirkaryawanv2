<?php

namespace App\Models;

use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PengajuanIzin extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengajuan_izin';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kode_izin';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_izin',
        'nik',
        'kode_cuti',
        'tgl_izin_dari',
        'tgl_izin_sampai',
        'status',
        'keterangan',
        'status_approved',
        'doc_sid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_izin_dari' => 'date',
        'tgl_izin_sampai' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the karyawan that owns the pengajuan izin.
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Get the cuti associated with the pengajuan izin.
     */
    public function cuti(): BelongsTo
    {
        return $this->belongsTo(PengajuanCuti::class, 'kode_cuti', 'kode_cuti');
    }

    /**
     * Scope a query to only include pending izin.
     */
    public function scopePending($query)
    {
        return $query->where('status_approved', '0');
    }

    /**
     * Scope a query to only include approved izin.
     */
    public function scopeApproved($query)
    {
        return $query->where('status_approved', '1');
    }

    /**
     * Scope a query to only include rejected izin.
     */
    public function scopeRejected($query)
    {
        return $query->where('status_approved', '2');
    }

    /**
     * Scope a query to filter by status type.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $dari, $sampai)
    {
        return $query->whereBetween('tgl_izin_dari', [$dari, $sampai]);
    }

    /**
     * Get the status text attribute.
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'i' => 'Izin',
            's' => 'Sakit',
            'c' => 'Cuti',
            default => 'Unknown'
        };
    }

    /**
     * Get the status approved text attribute.
     */
    public function getStatusApprovedTextAttribute(): string
    {
        return match ($this->status_approved) {
            '0' => 'Menunggu',
            '1' => 'Disetujui',
            '2' => 'Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * Get the status approved badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_approved) {
            '0' => 'badge-warning',
            '1' => 'badge-success',
            '2' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    /**
     * Get the durasi (duration in days).
     */
    public function getDurasiAttribute(): int
    {
        return $this->tgl_izin_dari->diffInDays($this->tgl_izin_sampai) + 1;
    }

    /**
     * Check if izin can be deleted.
     */
    public function canBeDeleted(): bool
    {
        return $this->status_approved === '0'; // Only pending can be deleted
    }

    /**
     * Check if izin can be edited.
     */
    public function canBeEdited(): bool
    {
        return $this->status_approved === '0'; // Only pending can be edited
    }

    /**
     * Get document URL.
     */
    public function getDocumentUrlAttribute(): ?string
    {
        if (!$this->doc_sid) {
            return null;
        }

        return \Storage::url('uploads/sid/' . $this->doc_sid);
    }

    /**
     * Check if has document.
     */
    public function hasDocument(): bool
    {
        return !empty($this->doc_sid) && \Storage::exists('public/uploads/sid/' . $this->doc_sid);
    }
}
