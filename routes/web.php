<?php


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ChantController;
use App\Http\Controllers\Admin\FinanceCategoryController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\RepetitionController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SousMenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/a-propos', [PublicController::class, 'about'])->name('about');
Route::get('/membres', [PublicController::class, 'members'])->name('members');
Route::get('/profil/{slug}', [PublicController::class, 'memberProfile'])->name('profile.show');
Route::post('/membres/{slug}/like', [PublicController::class, 'toggleLike'])->name('members.like');
Route::get('/evenements', [PublicController::class, 'events'])->name('events');
Route::get('/evenements/{id}', [PublicController::class, 'eventShow'])->name('evenements.show');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactSubmit'])->name('contact.submit');
Route::post('/newsletter', [PublicController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');
Route::get('/don', [\App\Http\Controllers\DonationController::class, 'index'])->name('donation');
Route::get('/don/success', [\App\Http\Controllers\DonationController::class, 'success'])->name('donation.success');

Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register');

// Supabase Auth Sync API
Route::post('/api/supabase-login', [\App\Http\Controllers\Auth\AuthSyncController::class, 'login']);

// Password reset routes
Route::get('/password/reset', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/email', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset')->where('token', '.*');
Route::post('/password/reset', [\App\Http\Controllers\Auth\PasswordResetController::class, 'resetPassword'])->name('password.update');
Route::post('/api/supabase-register', [\App\Http\Controllers\Auth\AuthSyncController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\Auth\AuthSyncController::class, 'logout'])->name('logout');

// Member Dashboard Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Admin only routes
    Route::resource('members', \App\Http\Controllers\Admin\MemberController::class);
    Route::post('members/{member}/toggle', [\App\Http\Controllers\Admin\MemberController::class, 'toggleStatus'])->name('members.toggle');
    Route::resource('pupitres', \App\Http\Controllers\Admin\PupitreController::class);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('chants', \App\Http\Controllers\Admin\ChantController::class);
    Route::name('finance.')->group(function () {
        Route::resource('finance-categories', \App\Http\Controllers\Admin\FinanceCategoryController::class);
        Route::resource('transactions', \App\Http\Controllers\Admin\TransactionController::class);
        Route::get('finance/export-csv', [\App\Http\Controllers\Admin\FinanceReportController::class, 'exportCSV'])->name('export.csv');
        Route::get('finance/report-pdf', [\App\Http\Controllers\Admin\FinanceReportController::class, 'reportPDF'])->name('report.pdf');
    });
    Route::resource('projets', \App\Http\Controllers\Admin\ProjectController::class);
    Route::resource('donations', \App\Http\Controllers\Admin\DonationController::class);
    Route::resource('repetitions', \App\Http\Controllers\Admin\RepetitionController::class);
    Route::post('repetitions/automate', [\App\Http\Controllers\Admin\RepetitionController::class, 'automate'])->name('repetitions.automate');
    Route::post('repetitions/{repetition}/sync-chants', [\App\Http\Controllers\Admin\RepetitionController::class, 'syncChants'])->name('suivi.repetitions.sync_chants');
    Route::resource('presences', \App\Http\Controllers\Admin\PresenceController::class);
    Route::resource('modules', \App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);
    Route::resource('sousmenus', \App\Http\Controllers\Admin\SousMenuController::class);
    Route::resource('events/types', \App\Http\Controllers\Admin\TypeController::class);
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
    Route::delete('events/images/{image}', [\App\Http\Controllers\Admin\EventController::class, 'deleteImage'])->name('events.delete-image');
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::post('fichier-chants', [\App\Http\Controllers\Admin\FichierChantController::class, 'store'])->name('fichier-chants.store');
    Route::post('chants/{chant}/record', [\App\Http\Controllers\Admin\FichierChantController::class, 'record'])->name('chants.record');
    Route::delete('fichier-chants/{fichierChant}', [\App\Http\Controllers\Admin\FichierChantController::class, 'destroy'])->name('fichier-chants.destroy');
});

// Choriste specific routes
Route::middleware(['auth'])->prefix('choriste')->name('choriste.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Bibliothèque Musicale
    Route::get('/chants', [\App\Http\Controllers\Choriste\ChantController::class, 'index'])->name('chants.index');
    Route::get('/chants/{chant}', [\App\Http\Controllers\Choriste\ChantController::class, 'show'])->name('chants.show');

    // Enregistrements
    Route::post('/enregistrements', [\App\Http\Controllers\Choriste\EnregistrementController::class, 'store'])->name('enregistrements.store');
    Route::delete('/enregistrements/{enregistrement}', [\App\Http\Controllers\Choriste\EnregistrementController::class, 'destroy'])->name('enregistrements.destroy');
});

// Admin Enregistrements Feedback
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/enregistrements', [\App\Http\Controllers\Admin\EnregistrementController::class, 'index'])->name('enregistrements.index');
    Route::post('/enregistrements/{enregistrement}/feedback', [\App\Http\Controllers\Admin\EnregistrementController::class, 'feedback'])->name('enregistrements.feedback');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__.'/auth.php';
