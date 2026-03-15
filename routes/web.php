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
use App\Http\Controllers\Admin\EventProgramController;
use App\Http\Controllers\Admin\PartieEventController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class , 'index'])->name('home');
Route::get('/a-propos', [PublicController::class , 'about'])->name('about');
Route::get('/membres', [PublicController::class , 'members'])->name('members');
Route::get('/profil/{slug}', [PublicController::class , 'memberProfile'])->name('profile.show');
Route::post('/membres/{slug}/like', [PublicController::class , 'toggleLike'])->name('members.like');
Route::get('/evenements', [PublicController::class , 'events'])->name('events');
Route::get('/evenements/{id}', [PublicController::class , 'eventShow'])->name('evenements.show');
Route::get('/evenements/{event}/programme', [\App\Http\Controllers\PublicEventController::class , 'program'])->name('event.program');
Route::get('/contact', [PublicController::class , 'contact'])->name('contact');
Route::post('/contact', [PublicController::class , 'contactSubmit'])->name('contact.submit');
Route::post('/newsletter', [PublicController::class , 'newsletterSubscribe'])->name('newsletter.subscribe');
Route::get('/don', [\App\Http\Controllers\DonationController::class , 'index'])->name('donation');
Route::get('/don/success', [\App\Http\Controllers\DonationController::class , 'success'])->name('donation.success');

Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register');

// Supabase Auth Sync API
Route::post('/api/supabase-login', [\App\Http\Controllers\Auth\AuthSyncController::class , 'login']);
Route::post('/api/sync-profile', [\App\Http\Controllers\Auth\AuthSyncController::class , 'syncProfile'])->middleware('auth');
Route::get('/api/profile', [\App\Http\Controllers\Auth\AuthSyncController::class , 'getProfile'])->middleware('auth');
Route::get('/api/dashboard/stats', [\App\Http\Controllers\Api\DashboardController::class , 'getStats'])->middleware('auth');
Route::post('/api/sondages', [\App\Http\Controllers\Api\SondageController::class , 'update'])->middleware('auth');
Route::post('/api/user/fcm-token', [\App\Http\Controllers\Auth\AuthSyncController::class , 'updateFcmToken'])->middleware('auth');

// Password reset routes
Route::get('/password/reset', [\App\Http\Controllers\Auth\PasswordResetController::class , 'showRequestForm'])->name('password.request');
Route::post('/password/email', [\App\Http\Controllers\Auth\PasswordResetController::class , 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class , 'showResetForm'])->name('password.reset')->where('token', '.*');
Route::post('/password/reset', [\App\Http\Controllers\Auth\PasswordResetController::class , 'resetPassword'])->name('password.update');
Route::post('/api/supabase-register', [\App\Http\Controllers\Auth\AuthSyncController::class , 'register']);
Route::post('/logout', [\App\Http\Controllers\Auth\AuthSyncController::class , 'logout'])->name('logout');

// Password change routes (forced)
Route::middleware('auth')->group(function () {
    Route::get('/password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class , 'show'])->name('password.change');
    Route::post('/password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class , 'update'])->name('password.change.update');
});

// Member Dashboard Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class , 'index'])->name('dashboard');
    // Admin only routes
    Route::resource('members', \App\Http\Controllers\Admin\MemberController::class);
    Route::post('members/{member}/toggle', [\App\Http\Controllers\Admin\MemberController::class , 'toggleStatus'])->name('members.toggle');
    Route::get('audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class , 'index'])->name('audit-logs.index');
    Route::resource('pupitres', \App\Http\Controllers\Admin\PupitreController::class);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('chants', \App\Http\Controllers\Admin\ChantController::class);
    Route::get('chants/{chant}/download', [\App\Http\Controllers\Admin\ChantController::class , 'downloadMain'])->name('chants.download');

    Route::name('finance.')->group(function () {
            Route::resource('finance-categories', \App\Http\Controllers\Admin\FinanceCategoryController::class);
            Route::resource('transactions', \App\Http\Controllers\Admin\TransactionController::class);
            Route::get('transactions/{transaction}/download', [\App\Http\Controllers\Admin\TransactionController::class , 'downloadJustificatif'])->name('transactions.download');
            Route::get('finance/export-excel', [\App\Http\Controllers\Admin\FinanceReportController::class , 'exportExcel'])->name('export.excel');
            Route::get('finance/report-pdf', [\App\Http\Controllers\Admin\FinanceReportController::class , 'reportPDF'])->name('report.pdf');
        }
        );
        Route::resource('projets', \App\Http\Controllers\Admin\ProjectController::class);
        Route::resource('donations', \App\Http\Controllers\Admin\DonationController::class);
        Route::resource('repetitions', \App\Http\Controllers\Admin\RepetitionController::class);
        Route::get('/repetitions/automate', [RepetitionController::class , 'automate'])->name('repetitions.automate'); // Pas utilisé mais au cas où
        Route::post('/repetitions/automate', [RepetitionController::class , 'automate'])->name('repetitions.automate');
        Route::post('/repetitions/{repetition}/reminder', [RepetitionController::class , 'sendReminder'])->name('repetitions.reminder');
        Route::post('repetitions/{repetition}/sync-chants', [\App\Http\Controllers\Admin\RepetitionController::class , 'syncChants'])->name('suivi.repetitions.sync_chants');
        Route::resource('presences', \App\Http\Controllers\Admin\PresenceController::class);
        Route::resource('modules', \App\Http\Controllers\Admin\ModuleController::class);
        Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);
        Route::resource('sousmenus', \App\Http\Controllers\Admin\SousMenuController::class);
        Route::resource('events/types', \App\Http\Controllers\Admin\TypeController::class);
        Route::get('events/api', [\App\Http\Controllers\Admin\EventController::class , 'api'])->name('events.api');
        Route::resource('events', \App\Http\Controllers\Admin\EventController::class);

        // Configuration des Parties (Global)
        Route::resource('partie-events', \App\Http\Controllers\Admin\PartieEventController::class);

        // Programme des événements
        Route::get('events/{event}/program', [EventProgramController::class , 'index'])->name('events.program.index');
        Route::get('events/{event}/repertoire/pdf', [EventProgramController::class , 'downloadPdf'])->name('events.repertoire.pdf');
        Route::post('events/{event}/repertoire', [EventProgramController::class , 'storeRepertoire'])->name('events.repertoire.store');
        Route::post('events/{event}/program/toggle-visibility', [EventProgramController::class , 'toggleVisibility'])->name('events.program.toggle-visibility');
        Route::delete('repertoire/{id}', [EventProgramController::class , 'destroyRepertoire'])->name('events.repertoire.destroy');

        Route::delete('events/images/{image}', [\App\Http\Controllers\Admin\EventController::class , 'deleteImage'])->name('events.delete-image');

        // Newsletter
        Route::get('newsletter', [\App\Http\Controllers\Admin\NewsletterController::class , 'index'])->name('newsletter.index');
        Route::get('newsletter/create', [\App\Http\Controllers\Admin\NewsletterController::class , 'create'])->name('newsletter.create');
        Route::post('newsletter/send', [\App\Http\Controllers\Admin\NewsletterController::class , 'send'])->name('newsletter.send');
        Route::delete('newsletter/{subscription}', [\App\Http\Controllers\Admin\NewsletterController::class , 'destroy'])->name('newsletter.destroy');
        Route::post('newsletter/{subscription}/toggle', [\App\Http\Controllers\Admin\NewsletterController::class , 'toggleStatus'])->name('newsletter.toggle');

        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
        Route::post('fichier-chants', [\App\Http\Controllers\Admin\FichierChantController::class , 'store'])->name('fichier-chants.store');
        Route::post('chants/{chant}/record', [\App\Http\Controllers\Admin\FichierChantController::class , 'record'])->name('chants.record');
        Route::get('fichier-chants/{fichierChant}/download', [\App\Http\Controllers\Admin\FichierChantController::class , 'download'])->name('fichier-chants.download');
        Route::delete('fichier-chants/{fichierChant}', [\App\Http\Controllers\Admin\FichierChantController::class , 'destroy'])->name('fichier-chants.destroy');
    });

// Choriste specific routes
Route::middleware(['auth'])->prefix('choriste')->name('choriste.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class , 'index'])->name('dashboard');

    // Bibliothèque Musicale
    Route::get('/chants', [\App\Http\Controllers\Choriste\ChantController::class , 'index'])->name('chants.index');
    Route::get('/chants/{chant}', [\App\Http\Controllers\Choriste\ChantController::class , 'show'])->name('chants.show');

    // Agenda des Événements
    Route::get('/agenda', [\App\Http\Controllers\Choriste\EventController::class , 'index'])->name('events.index');
    Route::get('/agenda/{event}', [\App\Http\Controllers\Choriste\EventController::class , 'show'])->name('events.show');
    Route::get('/agenda/{event}/repertoire/pdf', [\App\Http\Controllers\Admin\EventProgramController::class , 'downloadPdf'])->name('events.repertoire.pdf');

    // Répétitions
    Route::get('/repetitions', [\App\Http\Controllers\Choriste\RepetitionController::class , 'index'])->name('repetitions.index');
    Route::get('/repetitions/{repetition}/repertoire', [\App\Http\Controllers\Choriste\RepetitionController::class , 'repertoire'])->name('repetitions.repertoire');

    // Enregistrements
    Route::post('/enregistrements', [\App\Http\Controllers\Choriste\EnregistrementController::class , 'store'])->name('enregistrements.store');
    Route::delete('/enregistrements/{enregistrement}', [\App\Http\Controllers\Choriste\EnregistrementController::class , 'destroy'])->name('enregistrements.destroy');
});

// Admin Enregistrements Feedback
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/enregistrements', [\App\Http\Controllers\Admin\EnregistrementController::class , 'index'])->name('enregistrements.index');
    Route::post('/enregistrements/{enregistrement}/feedback', [\App\Http\Controllers\Admin\EnregistrementController::class , 'feedback'])->name('enregistrements.feedback');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // Notifications
    Route::post('/notifications/mark-all-as-read', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        }
        )->name('notifications.markAllAsRead');
    });

// require __DIR__.'/auth.php';
