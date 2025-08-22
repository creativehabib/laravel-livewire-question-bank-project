<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Role;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Questions;
use App\Livewire\Admin\Questions\Create;
use App\Livewire\Admin\Questions\Edit;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Subjects\Index as SubjectIndex;
use App\Livewire\Admin\Subjects\Create as SubjectCreate;
use App\Livewire\Admin\Subjects\Edit as SubjectEdit;
use App\Livewire\Admin\Chapters\Index as ChapterIndex;
use App\Livewire\Admin\Chapters\Create as ChapterCreate;
use App\Livewire\Admin\Chapters\Edit as ChapterEdit;
use App\Livewire\Admin\Tags\Index as TagIndex;
use App\Livewire\Admin\Users\Index as UserIndex;
use App\Livewire\Admin\Jobs\Index as JobIndex;
use App\Livewire\Admin\Jobs\Create as JobCreate;
use App\Livewire\Admin\Jobs\Edit as JobEdit;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Student\Dashboard as StudentDashboard;
use App\Livewire\Practice;
Route::view('/', 'frontend.landing')->name('landing');
Route::view('/frontend', 'frontend.landing');


Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');

    // Questions
    Route::get('/admin/questions', Questions::class)->name('admin.questions.index');
    Route::get('/admin/questions/create', Create::class)->name('admin.questions.create');
    Route::get('/admin/questions/{question}/edit', Edit::class)->name('admin.questions.edit');

    // Tags
    Route::get('/admin/tags', TagIndex::class)->name('admin.tags.index');

    // Subjects
    Route::get('/admin/subjects', SubjectIndex::class)->name('admin.subjects.index');
    Route::get('/admin/subjects/create', SubjectCreate::class)->name('admin.subjects.create');
    Route::get('/admin/subjects/{subject}/edit', SubjectEdit::class)->name('admin.subjects.edit');

    // Chapters
    Route::get('/admin/chapters', ChapterIndex::class)->name('admin.chapters.index');
    Route::get('/admin/chapters/create', ChapterCreate::class)->name('admin.chapters.create');
    Route::get('/admin/chapters/{chapter}/edit', ChapterEdit::class)->name('admin.chapters.edit');

    // Jobs
    Route::get('/admin/jobs', JobIndex::class)->name('admin.jobs.index');
    Route::get('/admin/jobs/create', JobCreate::class)->name('admin.jobs.create');
    Route::get('/admin/jobs/{job}/edit', JobEdit::class)->name('admin.jobs.edit');

    // Settings
    Route::get('/admin/settings', Settings::class)->name('admin.settings');

    // Users
    Route::get('/admin/users', UserIndex::class)->name('admin.users.index');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', TeacherDashboard::class)->name('teacher.dashboard');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', StudentDashboard::class)->name('student.dashboard');
});

Route::middleware(['auth', 'role:teacher,student'])->group(function () {
    // Practice
    Route::get('/practice', Practice::class)->name('practice');
});

Route::get('/dashboard', function () {
    $user = request()->user();

    return match ($user->role) {
        Role::ADMIN => redirect()->route('admin.dashboard'),
        Role::TEACHER => redirect()->route('teacher.dashboard'),
        Role::STUDENT => redirect()->route('student.dashboard'),
    };
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::view('/profile', 'profile')->name('profile');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/questions', Questions::class)->name('teacher.questions.index');
    Route::get('/teacher/questions/create', Create::class)->name('teacher.questions.create');
    Route::get('/teacher/questions/{question}/edit', Edit::class)->name('teacher.questions.edit');
});
include __DIR__.'/auth.php';

Route::fallback(function () {
    return redirect()->route('landing');
});
