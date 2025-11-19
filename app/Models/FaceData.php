<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceData extends Model
{
    use HasFactory;

    protected $table = 'face_data';

    protected $fillable = [
        'nik',
        'face_descriptor',
        'face_image',
        'status',
        'enrollment_count',
        'last_updated'
    ];

    protected $casts = [
        'last_updated' => 'datetime'
    ];

    /**
     * Relationship dengan Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Get face descriptor sebagai array
     */
    public function getFaceDescriptorArray()
    {
        return json_decode($this->face_descriptor, true);
    }

    /**
     * Set face descriptor dari array
     */
    public function setFaceDescriptorArray($descriptor)
    {
        $this->face_descriptor = json_encode($descriptor);
    }
}