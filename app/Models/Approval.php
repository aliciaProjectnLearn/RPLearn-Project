<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'teacher_id',
        'status',
        'comment',
        'approved_at',
    ];

    // Pastikan format tanggal dibaca dengan benar
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // === RELATIONS ===

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function teacher()
    {
        // Mengarah ke User::class sama seperti di Module.php
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
