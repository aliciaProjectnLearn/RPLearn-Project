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
        'order',
        'video_url',
        'file_path',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
