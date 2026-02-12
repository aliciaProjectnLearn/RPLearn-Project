<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class ModuleLike extends Model
{
    protected $fillable = [
    'user_id',
    'module_id'
];

public function module()
{
    return $this->belongsTo(Module::class);
}

}
