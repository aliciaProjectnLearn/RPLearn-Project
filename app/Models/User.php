<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Termwind\Question;
use App\Models\Module;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'username',
        'password',
        'role', // enum('siswa','guru', 'admin')
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ================= RELATIONS =================

    // User → Student (1-1)
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    // Guru → Modules (1-N)
    public function modules()
    {
        return $this->hasMany(Module::class, 'teacher_id');
    }

    // Guru → Questions
    public function questions()
    {
        return $this->hasMany(Question::class, 'teacher_id');
    }

    public function savedModules()
    {
        return $this->belongsToMany(Module::class,'save_modules');
    }


    public function moduleApprovals()
    {
        // 1 User (Guru) bisa punya banyak history approval dari modul-modulnya
        return $this->hasMany(approvals::class, 'teacher_id');
    }
}
