<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Questions\Index;
use App\Livewire\Admin\Questions\Create;
use App\Livewire\Admin\Questions\Edit;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Subjects\Index as SubjectIndex;
use App\Livewire\Admin\Subjects\Create as SubjectCreate;
use App\Livewire\Admin\Subjects\Edit as SubjectEdit;
use App\Livewire\Admin\Chapters\Index as ChapterIndex;
use App\Livewire\Admin\Chapters\Create as ChapterCreate;
use App\Livewire\Admin\Chapters\Edit as ChapterEdit;
use App\Livewire\Admin\Students\Index as StudentIndex;
use App\Livewire\Admin\Tags\Index as TagIndex;
use App\Livewire\Practice;
Route::view('/', 'frontend.landing')->name('landing');
Route::view('/frontend', 'frontend.landing');


Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');

    // Questions
    Route::get('/admin/questions', Index::class)->name('admin.questions.index');
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

    // Settings
    Route::get('/admin/settings', Settings::class)->name('admin.settings');

    // Students
    // Route::get('/admin/students', StudentIndex::class)->name('admin.students.index');
});

Route::middleware(['auth', 'role:teacher,student'])->group(function () {
    // Practice
    Route::get('/practice', Practice::class)->name('practice');
});

Route::middleware('auth')->group(function () {
    Route::view('/profile', 'profile')->name('profile');
});

include __DIR__.'/auth.php';

Route::fallback(function () {
    return redirect()->route('landing');
});
