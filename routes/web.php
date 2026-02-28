<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\StudentQuestionController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Teacher\ModuleStatController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\DictionaryController;
use App\Http\Controllers\Teacher\DictionaryController as TeacherDictionaryController;
use App\Http\Controllers\Student\DictionaryController as StudentDictionaryController;
use App\Http\Controllers\Student\ModulesAllController as StudentModuleAllController;
use App\Http\Controllers\Student\SavedModuleController;


/*
|--------------------------------------------------------------------------
| Root Redirect Logic
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru'  => redirect()->route('teacher.dashboard'),
            default => redirect()->route('student.dashboard'),
        };
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Trello Task #15)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // 1. RUTE KHUSUS ISI MATERI (WAJIB ADA)
    Route::get('modules/{id}/add-content', [App\Http\Controllers\Admin\ModuleController::class, 'addContent'])
        ->name('modules.addContent');
    Route::post('modules/{id}/store-content', [App\Http\Controllers\Admin\ModuleController::class, 'storeContent'])
        ->name('modules.storeContent');

    // 2. RESOURCE UTAMA
    Route::post('modules/approve-all', [App\Http\Controllers\Admin\ModuleController::class, 'approveAll'])->name('modules.approveAll');
    Route::resource('modules', App\Http\Controllers\Admin\ModuleController::class);
    // Rute untuk review/persetujuan modul oleh Admin
    Route::put('modules/{id}/review', [App\Http\Controllers\Admin\ModuleController::class, 'review'])->name('modules.review');
    Route::resource('dictionaries', App\Http\Controllers\Admin\DictionaryController::class);
    Route::resource('users', UserController::class);

    // 3. FITUR FAQ

    Route::get('faq/{id}', [App\Http\Controllers\Admin\FaqController::class, 'show'])->name('faq.show');

    Route::get('faq', [App\Http\Controllers\Admin\FAQController::class, 'index'])->name('faq.index');
    Route::post('faq/{id}/answer', [App\Http\Controllers\Admin\FAQController::class, 'answer'])->name('faq.answer');

    // 4. FITUR DETAIL BUAT DI SUB-MATERI.
    Route::get('modules/content/{content}', [App\Http\Controllers\Admin\ModuleController::class, 'showContent'])->name('modules.content.show');

    // 5. FITUR DELETE BUAT DI SUB-MATERI
    Route::delete('modules/content/{content}', [App\Http\Controllers\Admin\ModuleController::class, 'destroyContent'])->name('modules.content.destroy');

    // 6. FITUR EDIT BUAT DI SUB-MATERI
    Route::get('modules/content/{content}/edit', [App\Http\Controllers\Admin\ModuleController::class, 'editContent'])->name('modules.content.edit');

    Route::put('modules/content/{content}', [App\Http\Controllers\Admin\ModuleController::class, 'updateContent'])->name('modules.content.update');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentModuleController::class, 'index'])->name('dashboard');
    Route::get('/dictionary', [StudentDictionaryController::class, 'index'])->name('dictionary.index');
    Route::get('/modules', [StudentModuleAllController::class, 'index'])->name('modules.index');

Route::get('/modules/{id}/json', function($id) {

    $module = \App\Models\Module::with([
        'contents',
        'gradeCategory',
        'subjectCategory',
        'teacher'
    ])->findOrFail($id);

    $module->isSaved = $module->savedByUsers()
        ->where('user_id', auth()->id())
        ->exists();

    return response()->json($module);
});

    Route::get('/modules/saved',[SavedModuleController::class,'index'])->name('modules.saved');
    Route::post('/modules/{module}/save',[SavedModuleController::class,'toggleSave'])->name('modules.save');
    Route::get('/modules/{id}', [StudentModuleController::class, 'show'])->name('modules.show');

    Route::resource('/questions', StudentQuestionController::class);

    Route::get('/faq', [FAQController::class, 'all'])->name('faq.index');
    Route::post('/faq', [FAQController::class, 'store'])->name('faq.store');
    Route::get('/faq/list', [FAQController::class, 'index'])->name('faq.list');
    Route::get('/faq/{id}', [FAQController::class, 'show'])->name('faq.show');

});

/*
|--------------------------------------------------------------------------
| Shared Routes (Teacher & Profile)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard');

    Route::resource('modules', AdminModuleController::class);

    Route::resource('dictionaries', App\Http\Controllers\Teacher\DictionaryController::class);

    Route::get('faq', [App\Http\Controllers\Teacher\FAQController::class, 'index'])->name('faq.index');
    Route::post('/faq/answer/{id}', [App\Http\Controllers\Teacher\FAQController::class, 'answer'])->name('faq.answer');
    Route::put('/teacher/faq/{answer}/update', [App\Http\Controllers\Teacher\FAQController::class, 'update'])->name('faq.update');
    Route::delete('/faq/{answer}/delete', [App\Http\Controllers\Teacher\FAQController::class, 'destroy'])->name('faq.delete');
    Route::get('modules/{id}/statistik', [App\Http\Controllers\Teacher\ModuleStatController::class, 'show'])->name('modules.statistik');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

Route::post('/modules/{id}/like', [StudentModuleController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('modules.like');

    Route::middleware('auth')->group(function () {
        Route::post('/modules/{id}/save', [StudentModuleController::class, 'save'])
            ->name('modules.save');
        Route::delete('/modules/{id}/unsave', [StudentModuleController::class, 'unsave'])
            ->name('modules.unsave');
    });


require __DIR__ . '/auth.php';
