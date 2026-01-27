<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    protected $fillable = [
        'module_id',
        'term',
        'definition',
    ];

    // Relasi ke module (opsional, tapi disiapkan)
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
