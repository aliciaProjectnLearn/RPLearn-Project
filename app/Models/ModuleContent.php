<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleContent extends Model
{
    use HasFactory;

    protected $table = 'module_content';

    protected $fillable = [
        'module_id',
        'title',
        'content',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
