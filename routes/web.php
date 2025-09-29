<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OutboxController;
use App\Http\Controllers\InboxController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('mail')->name('mail.')->group(function () {
    // Outbox: resource本体
    Route::resource('outbox', OutboxController::class)
        ->only(['index','store','show','edit','update','destroy'])
        ->names('outbox');

    // ★ Outbox: 一括削除（/mail/outbox に DELETE）
    Route::delete('outbox', [OutboxController::class, 'bulkDestroy'])
        ->name('outbox.bulk-destroy');

    // Inbox: （すでにあるやつ）
    Route::resource('inbox', InboxController::class)
        ->only(['index','store','show','edit','update','destroy'])
        ->names('inbox');

    Route::delete('inbox', [InboxController::class, 'bulkDestroy'])->name('inbox.bulk-destroy');
    Route::post('inbox/{inbox}/restore', [InboxController::class, 'restore'])->name('inbox.restore');
});
require __DIR__.'/auth.php';
