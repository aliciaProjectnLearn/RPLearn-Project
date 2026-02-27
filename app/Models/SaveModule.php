<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveModule extends Model
{
    protected $table = 'save_modules';
    protected $fillable = ['user_id','module_id'];
}

