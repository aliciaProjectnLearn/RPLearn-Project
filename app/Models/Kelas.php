<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = ['nama'];

    public function students()
    {
        return $this->hasMany(Student::class, 'kelas_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'kelas_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'kelas_id');
    }
}
