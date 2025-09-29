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
    // Outbox (index, store, destroyを定義)
    Route::resource('outbox', OutboxController::class)->only([
        'index', 'store', 'destroy'
    ]);

    // Inbox (indexを定義)
    Route::resource('inbox', InboxController::class)->only([
        'index'
    ]);

    // Inboxのカスタムアクション
    // Route::resourceの外に定義することで、カスタムアクションとして維持
    Route::post('/inbox/receive', [InboxController::class, 'receive'])->name('inbox.receive'); // 疑似受信
    Route::patch('/inbox/{inbox}/toggle-read', [InboxController::class, 'toggleRead'])->name('inbox.toggle');
});

require __DIR__.'/auth.php';
