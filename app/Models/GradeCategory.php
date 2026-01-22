<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeCategory extends Model
{
    use HasFactory;

    protected $table = 'grade_categories';

    protected $fillable = [
        'grade',
    ];

    // Grade → Modules
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
