<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;


// Page d'accueil / fil d'actualité central
Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Auth
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Public
Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('announcements/{id}', [AnnouncementController::class, 'show'])->name('announcements.show');

// Publications publiques
Route::resource('posts', PostController::class)->only(['index', 'show']);

// Routes nécessitant authentification
Route::middleware('auth')->group(function () {
    // Posts protégés
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::post('posts/{post}/toggle-like', [PostController::class, 'toggleLike'])->name('posts.toggleLike');

    // Comments (nested-ish)
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Profil
    Route::get('profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Groupes
    Route::resource('groups', GroupController::class);
    Route::post('groups/{group}/add-member', [GroupController::class, 'addMember'])->name('groups.addMember');
    Route::delete('groups/{group}/remove-member', [GroupController::class, 'removeMember'])->name('groups.removeMember');

    // Événements
    Route::resource('events', EventController::class);
    Route::post('events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('events/{event}/unregister', [EventController::class, 'unregister'])->name('events.unregister');

    // Messagerie
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('messages-search', [MessageController::class, 'search'])->name('messages.search');
});

// Public profile
Route::get('profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

// Admin routes (protection via middleware class)
Route::prefix('admin')->middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/validate', [AdminController::class, 'validateUser'])->name('admin.users.validate');
    Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('admin.users.suspend');
    Route::post('/users/{user}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');

    Route::get('/posts/reported', [AdminController::class, 'reportedPosts'])->name('admin.posts.reported');
    Route::post('/posts/{post}/approve', [AdminController::class, 'approvePost'])->name('admin.posts.approve');
    Route::delete('/posts/{post}', [AdminController::class, 'deletePost'])->name('admin.posts.delete');

    Route::get('/events/pending', [AdminController::class, 'pendingEvents'])->name('admin.events.pending');
    Route::post('/events/{event}/approve', [AdminController::class, 'approveEvent'])->name('admin.events.approve');
    Route::post('/events/{event}/reject', [AdminController::class, 'rejectEvent'])->name('admin.events.reject');

    // Reports moderation view (uses reports blade)
    Route::get('/reports', [AdminController::class, 'reportedPosts'])->name('admin.reports.index');
});
