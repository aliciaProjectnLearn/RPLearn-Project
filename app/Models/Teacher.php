<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['user_id', 'name', 'nip'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Many-to-many: satu guru bisa mengampu banyak kelas
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'teacher_kelas', 'teacher_id', 'kelas_id');
    }
}
