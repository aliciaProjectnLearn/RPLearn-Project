<?php

namespace App\Http\Controllers\Student;

use App\Models\Module;
use App\Models\SaveModule;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SavedModuleController extends Controller
{
    public function toggleSave(Module $module)
    {
        $user = Auth::user();

        $saved = SaveModule::where('user_id',$user->id)
                    ->where('module_id',$module->id)
                    ->first();

        if ($saved) {
            $saved->delete();
            return response()->json(['saved'=>false]);
        }

        SaveModule::firstOrCreate([
            'user_id'=>$user->id,
            'module_id'=>$module->id
        ]);

        return response()->json(['saved'=>true]);
    }

    public function index()
    {
        $modules = Module::whereHas('savedByUsers', function($q){
            $q->where('user_id',auth()->id());
        })
        ->with(['teacher','gradeCategory','subjectCategory'])
        ->latest()
        ->get();

        return view('student.modules.saved', compact('modules'));
    }
}
