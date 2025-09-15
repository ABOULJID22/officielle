<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Models\Post;
Route::get('/', function () {
    $posts = Post::query()
        ->with('category')
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->orderByDesc('published_at')
        ->limit(3)
        ->get(['id','slug','title','content','cover_image','category_id','published_at']);
    return view('welcome', compact('posts'));
});

// Pages légales
Route::view('/mentions-legales', 'pages.legal')->name('legal');
Route::view('/politique-de-confidentialite', 'pages.privacy')->name('privacy');
// Page Pourquoi Offitrade
Route::view('/pourquoi-offitrade', 'pages.pourquoi')->name('pourquoi');

Route::fallback(function () {
    return response()->view('pages.404', [], 404);
});


/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/contact', function () {
    return view('pages.contact');
});
Route::get('/blog', [PostController::class, 'index'])->name('pages.blog.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('pages.blog.show'); // liaison par slug

require __DIR__.'/auth.php';
