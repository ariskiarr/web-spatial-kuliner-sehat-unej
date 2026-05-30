
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TempatMakanController;
use App\Http\Controllers\Admin\MenuMakanController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\User\TempatMakanController as UserTempatMakanController;

use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\ReviewController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {

    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/user/tempat-makan');
    }
    return redirect('/login');
});

// TEMPORARY DEBUG LOGIN - NO MIDDLEWARE
Route::get('/debug-login', function () {
    return view('auth.login');
});

Route::post('/debug-login', function (Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        
        file_put_contents(storage_path('logs/debug-login.txt'), 
            date('Y-m-d H:i:s') . " - Login success\n" .
            "User ID: {$user->id}\n" .
            "Email: {$user->email}\n" .
            "Role: " . ($user->role ?? 'NULL') . "\n" .
            "Redirecting to: " . ($user->role === 'admin' ? '/admin/dashboard' : '/user/tempat-makan') . "\n\n",
            FILE_APPEND
        );
        
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/user/tempat-makan');
    }
    
    return back()->withErrors(['email' => 'Invalid credentials']);
});

use App\Models\tempat_makan;
// Admin Dashboard Route

use App\Models\kategori;

Route::middleware(['auth'])->get('/admin/dashboard', function () {
    $tempatMakan = tempat_makan::with('kategori')->get();
    $categories = kategori::all();
    // inject nama_kategori ke setiap tempatMakan agar JS bisa akses
    $tempatMakan->transform(function($item) {
        $item->nama_kategori = $item->kategori ? $item->kategori->nama_kategori : '';
        $item->latitude = $item->getLatitude();
        $item->longitude = $item->getLongitude();
        return $item;
    });
    return view('admin.dashboard-admin', compact('tempatMakan', 'categories'));
})->name('admin.dashboard');


Route::get('/dashboard-user', [DashboardUserController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.user');

Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    // Detail tempat makan
    Route::get('/tempat-makan/{id}', [TempatMakanDetailController::class, 'show'])->name('tempat.show');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/tempat-makan/{id}/favorite', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    // Reviews
    Route::post('/tempat-makan/{id}/review', [ReviewController::class, 'store'])->name('review.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('tempat-makan', TempatMakanController::class);


    // Menu Makan Routes (nested under tempat-makan)
    Route::get('tempat-makan/{tempat_id}/menu', [MenuMakanController::class, 'index'])->name('menu-makan.index');
    Route::get('tempat-makan/{tempat_id}/menu/create', [MenuMakanController::class, 'create'])->name('menu-makan.create');
    Route::post('tempat-makan/{tempat_id}/menu', [MenuMakanController::class, 'store'])->name('menu-makan.store');
    Route::get('tempat-makan/{tempat_id}/menu/{id}/edit', [MenuMakanController::class, 'edit'])->name('menu-makan.edit');
    Route::put('tempat-makan/{tempat_id}/menu/{id}', [MenuMakanController::class, 'update'])->name('menu-makan.update');
    Route::delete('tempat-makan/{tempat_id}/menu/{id}', [MenuMakanController::class, 'destroy'])->name('menu-makan.destroy');

    // API for calorie estimation
    Route::post('menu/estimate-kalori', [MenuMakanController::class, 'getCalorieEstimate'])->name('menu.estimate-kalori');
});


// User Routes - Tempat Makan Features
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    // AJAX Routes for Spatial Features (must be before {id} route)
    Route::get('/tempat-makan/within-radius', [UserTempatMakanController::class, 'withinRadius'])->name('tempat-makan.within-radius');
    Route::get('/tempat-makan/nearest-from-kampus', [UserTempatMakanController::class, 'nearestFromKampus'])->name('tempat-makan.nearest-from-kampus');
    Route::get('/tempat-makan/search', [UserTempatMakanController::class, 'search'])->name('tempat-makan.search');
    Route::post('/tempat-makan/calculate-route', [UserTempatMakanController::class, 'calculateRoute'])->name('tempat-makan.calculate-route');
    
    // Tempat Makan Routes
    Route::get('/tempat-makan', [UserTempatMakanController::class, 'index'])->name('tempat-makan.index');
    Route::get('/tempat-makan/{id}', [UserTempatMakanController::class, 'show'])->name('tempat-makan.show')->where('id', '[0-9]+');
    
    // Favorites Routes
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::put('/favorites/{id}/note', [FavoriteController::class, 'updateNote'])->name('favorites.update-note');
    Route::get('/favorites/check/{tempatMakanId}', [FavoriteController::class, 'check'])->name('favorites.check');
    
    // Reviews Routes
    Route::get('/reviews', [ReviewController::class, 'myReviews'])->name('reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/reviews/tempat-makan/{tempatMakanId}', [ReviewController::class, 'getReviews'])->name('reviews.get');

});

require __DIR__.'/auth.php';
