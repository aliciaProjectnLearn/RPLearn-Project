<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModuleLike;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules';

    protected $fillable = [
        'teacher_id',
        'grade_category_id',
        'subject_category_id',
        'title',
        'desc',
        'track',
        'is_published',
        'like',
    ];

    // === RELATIONS ===

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function gradeCategory()
    {
        return $this->belongsTo(GradeCategory::class, 'grade_category_id');
    }

    public function subjectCategory()
    {
        return $this->belongsTo(SubjectCategory::class, 'subject_category_id');
    }

    public function contents()
    {
        return $this->hasMany(ModuleContent::class, 'module_id');
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class,'save_modules');
    }

    public function likes()
    {
        return $this->hasMany(ModuleLike::class, 'module_id');
    }

        public function isLiked()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

}
