<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleView extends Model
{
    protected $table = 'modul_views';

    protected $fillable = ['module_id', 'student_id', 'viewed_at'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
