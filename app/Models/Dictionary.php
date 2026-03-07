<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    protected $fillable = [
        'term',
        'definition',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
